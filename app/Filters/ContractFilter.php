<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ContractFilter
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $this->search($query);
        $this->filterByCounterparty($query);
        $this->filterByContractDate($query);
        $this->filterByEndDate($query);
        $this->filterByStatus($query);
        $this->sort($query);

        return $query;
    }

    protected function search(Builder $query): void
    {
        $search = $this->request->input('search');

        if (blank($search)) {
            return;
        }

        $query->where(function (Builder $query) use ($search) {
            $query->where('number', 'like', "%{$search}%")
                ->orWhereHas('counterparty', function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
        });
    }

    protected function filterByCounterparty(Builder $query): void
    {
        $counterpartyId = $this->request->input('counterparty_id');

        if (blank($counterpartyId)) {
            return;
        }

        $query->where('counterparty_id', $counterpartyId);
    }

    protected function filterByContractDate(Builder $query): void
    {
        $from = $this->request->input('contract_date_from');
        $to = $this->request->input('contract_date_to');

        if (filled($from)) {
            $query->whereDate('contract_date', '>=', $from);
        }

        if (filled($to)) {
            $query->whereDate('contract_date', '<=', $to);
        }
    }

    protected function filterByStatus(Builder $query): void
    {
        $status = $this->request->input('status');

        if (blank($status)) {
            return;
        }

        $today = today();

        match ($status) {
            'expired' => $query->whereDate('end_date', '<', $today),

            'expiring' => $query->whereBetween('end_date', [
                $today,
                $today->copy()->addMonth(),
            ]),

            'active' => $query->whereDate(
                'end_date',
                '>',
                $today->copy()->addMonth()
            ),

            default => null,
        };
    }

    protected function filterByEndDate(Builder $query): void
    {
        $from = $this->request->input('end_date_from');
        $to = $this->request->input('end_date_to');

        if (filled($from)) {
            $query->whereDate('end_date', '>=', $from);
        }

        if (filled($to)) {
            $query->whereDate('end_date', '<=', $to);
        }
    }

    protected function sort(Builder $query): void
    {
        $sort = $this->request->input('sort');

        if (blank($sort)) {
            return;
        }

        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';

        $column = ltrim($sort, '-');

        $query->orderBy($column, $direction);
    }
}
