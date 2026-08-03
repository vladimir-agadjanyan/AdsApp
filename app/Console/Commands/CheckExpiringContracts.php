<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-expiring-contracts')]
#[Description('Создает уведомления об истекающих договорах для администраторов и менеджеров')]
class CheckExpiringContracts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $contracts = Contract::query()
            ->with('counterparty')
            ->whereDate('end_date', '>=', today())
            ->whereDate('end_date', '<=', today()->copy()->addDays(30))
            ->get();

        $users = User::query()
            ->whereHas(
                'role',
                fn ($query) => $query->whereIn(
                    'name',
                    [
                        'Администратор',
                        'Менеджер',
                    ]
                )
            )
            ->get();

        $createdCount = 0;

        foreach ($contracts as $contract) {
            foreach ($users as $user) {
                $alreadyExists = Notification::query()
                    ->where('user_id', $user->id)
                    ->where('contract_id', $contract->id)
                    ->where('title', 'Договор скоро заканчивается')
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                Notification::query()->create([
                    'user_id' => $user->id,
                    'contract_id' => $contract->id,
                    'title' => 'Договор скоро заканчивается',
                    'message' => sprintf(
                        'Договор №%s с %s заканчивается %s.',
                        $contract->number,
                        $contract->counterparty->name,
                        $contract->end_date?->format('d.m.Y') ?? '—',
                    ),
                ]);

                $createdCount++;
            }
        }

        $this->info(
            "Создано уведомлений: {$createdCount}"
        );

        return self::SUCCESS;
    }
}