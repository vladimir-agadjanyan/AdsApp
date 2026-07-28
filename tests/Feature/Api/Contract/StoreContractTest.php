<?php

namespace Tests\Feature\Api\Contract;

use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_contract(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $data = [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0001',
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
            'end_date' => '2027-07-10',
            'note' => 'Тестовый договор',
        ];

        $response = $this->postJson('/api/contracts', $data);

        $response
            ->assertCreated()
            ->assertJsonPath('data.number', 'Д-2026-0001');

        $this->assertDatabaseHas('contracts', [
            'number' => 'Д-2026-0001',
            'counterparty_id' => $counterparty->id,
            'created_by' => $user->id,
        ]);
    }

    public function test_it_sets_created_by_authenticated_user(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0002',
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
            'end_date' => '2027-07-10',
        ]);

        $this->assertDatabaseHas('contracts', [
            'number' => 'Д-2026-0002',
            'created_by' => $user->id,
        ]);
    }

    public function test_it_requires_counterparty(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/contracts', [
            'number' => 'Д-2026-0001',
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
            'end_date' => '2027-07-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('counterparty_id');
    }

    public function test_it_requires_number(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
            'end_date' => '2027-07-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('number');
    }

    public function test_it_requires_contract_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0001',
            'start_date' => '2026-07-10',
            'end_date' => '2027-07-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('contract_date');
    }

    public function test_it_requires_start_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0001',
            'contract_date' => '2026-07-01',
            'end_date' => '2027-07-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('start_date');
    }

    public function test_it_requires_end_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0001',
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('end_date');
    }

    public function test_it_validates_end_date_after_or_equal_start_date(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $counterparty = Counterparty::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0001',
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-01',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('end_date');
    }

    public function test_it_requires_authentication(): void
    {
        $counterparty = Counterparty::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'counterparty_id' => $counterparty->id,
            'number' => 'Д-2026-0001',
            'contract_date' => '2026-07-01',
            'start_date' => '2026-07-10',
            'end_date' => '2027-07-10',
        ]);

        $response->assertUnauthorized();
    }
}
