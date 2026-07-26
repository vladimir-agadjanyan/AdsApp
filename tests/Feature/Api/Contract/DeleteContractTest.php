<?php

namespace Tests\Feature\Api\Contract;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_contract(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->deleteJson("/api/contracts/{$contract->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('contracts', [
            'id' => $contract->id,
        ]);
    }

    public function test_it_returns_404_when_contract_not_found(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/contracts/999999');

        $response->assertNotFound();
    }

    public function test_it_requires_authentication(): void
    {
        $contract = Contract::factory()->create();

        $response = $this->deleteJson("/api/contracts/{$contract->id}");

        $response->assertUnauthorized();
    }
}