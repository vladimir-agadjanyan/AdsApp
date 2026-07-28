<?php

namespace Tests\Feature\Api\Contract;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_contract(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $contract = Contract::factory()->create();

        $response = $this->getJson("/api/contracts/{$contract->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $contract->id);
    }

    public function test_it_returns_404_when_contract_not_found(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/contracts/999999');

        $response->assertNotFound();
    }
}
