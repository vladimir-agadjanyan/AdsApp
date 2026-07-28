<?php

namespace Tests\Feature\Api\Contract;

use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_contract(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $counterparty = Counterparty::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
            'end_date' => '2027-08-10',
            'note' => 'Обновленный договор',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.number', 'Д-2026-9999');

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-9999',
            'note' => 'Обновленный договор',
        ]);
    }

    public function test_it_requires_counterparty(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
            'end_date' => '2027-08-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('counterparty_id');
    }

    public function test_it_requires_number(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
            'end_date' => '2027-08-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('number');
    }

    public function test_it_requires_contract_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'number' => 'Д-2026-9999',
            'start_date' => '2026-08-10',
            'end_date' => '2027-08-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('contract_date');
    }

    public function test_it_requires_start_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'end_date' => '2027-08-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('start_date');
    }

    public function test_it_requires_end_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('end_date');
    }

    public function test_it_validates_end_date_after_or_equal_start_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
            'end_date' => '2026-08-01',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('end_date');
    }

    public function test_it_returns_404_when_contract_not_found(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/contracts/999999', [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
            'end_date' => '2027-08-10',
        ]);

        $response->assertNotFound();
    }

    public function test_it_requires_authentication(): void
    {
        $contract = Contract::factory()->create();

        $response = $this->putJson("/api/contracts/{$contract->id}", [
            'counterparty_id' => Counterparty::factory()->create()->id,
            'number' => 'Д-2026-9999',
            'contract_date' => '2026-08-01',
            'start_date' => '2026-08-10',
            'end_date' => '2027-08-10',
        ]);

        $response->assertUnauthorized();
    }
}
