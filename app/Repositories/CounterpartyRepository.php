<?php

namespace App\Repositories;

use App\DTO\Counterparties\CreateCounterpartyData;
use App\DTO\Counterparties\UpdateCounterpartyData;
use App\Models\Counterparty;

class CounterpartyRepository
{
    public function create(CreateCounterpartyData $data): Counterparty
    {
        return Counterparty::create([
            'name' => $data->name,
            'inn' => $data->inn,
            'phone' => $data->phone,
            'email' => $data->email,
            'address' => $data->address,
            'contact_person' => $data->contactPerson,
            'note' => $data->note,
        ]);
    }

    public function update(
        Counterparty $counterparty,
        UpdateCounterpartyData $data,
    ): Counterparty {
        $counterparty->update([
            'name' => $data->name,
            'inn' => $data->inn,
            'phone' => $data->phone,
            'email' => $data->email,
            'address' => $data->address,
            'contact_person' => $data->contactPerson,
            'note' => $data->note,
        ]);

        return $counterparty;
    }

    public function find(int $id): Counterparty
    {
        return Counterparty::findOrFail($id);
    }

    public function delete(Counterparty $counterparty): void
    {
        $counterparty->delete();
    }
}