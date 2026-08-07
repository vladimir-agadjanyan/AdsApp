<?php

namespace App\Services;

use App\Mail\ContractExpiringMail;
use App\Mail\MissingPhotoReportMail;
use App\Repositories\AdvertisingObjectRepository;
use App\Repositories\ContractRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function __construct(
        private readonly ContractRepository $contractRepository,
        private readonly UserRepository $userRepository,
        private readonly AdvertisingObjectRepository $advertisingObjectRepository,
    ) {}

    public function notifyExpiringContracts(): void
    {
        $managers = $this->userRepository
            ->findByRoleName('Менеджер');

        foreach ([30, 3, 2, 1] as $days) {
            $contracts = $this->contractRepository
                ->getExpiringContracts($days);

            foreach ($contracts as $contract) {
                foreach ($managers as $manager) {
                    Mail::to($manager->email)
                        ->send(
                            new ContractExpiringMail(
                                $contract,
                                $days,
                            )
                        );
                }
            }
        }
    }

    public function notifyMissingPhotoReports(): void
    {
        $managers = $this->userRepository
            ->findByRoleName('Менеджер');

        $objects = $this->advertisingObjectRepository
            ->getWithoutTodayPhotoReport();

        foreach ($objects as $object) {
            foreach ($managers as $manager) {
                Mail::to($manager->email)
                    ->send(
                        new MissingPhotoReportMail($object)
                    );
            }
        }
    }
}
