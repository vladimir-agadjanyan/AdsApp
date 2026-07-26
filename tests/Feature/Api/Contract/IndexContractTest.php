<?php

namespace Tests\Feature\Api\Contract;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contract;

class IndexContractTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'sanctum');
    }

    public function test_it_returns_contracts_list(): void
    {
        Contract::factory()->count(5)->create();

        $response = $this->getJson('/api/contracts');

        $response
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_it_returns_expected_structure(): void
    {
        Contract::factory()->create();
    
        $response = $this->getJson('/api/contracts');
    
        $response->assertOk();
    
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'number',
                    'contract_date',
                    'start_date',
                    'end_date',
                    'status',
                    'note',
                    'counterparty',
                    'created_by',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links',
            'meta',
        ]);
    }
}
