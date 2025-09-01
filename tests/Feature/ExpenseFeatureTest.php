<?php

namespace Tests\Feature;

use Tests\{TestCase, TestCaseWithModules};
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Modules\Expenses\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class ExpenseFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_can_create_expense(): void
    {
        User::factory()->create();

        // if your app requires authentication, use:
        // $user = User::factory()->create();
        // $this->actingAs($user);

        // $payload = Expense::factory()->count(1)->create();
        // $this->assertNotNull($payload->id); // Should be a UUID string
        // $this->assertTrue(Uuid::isValid($payload->id));

        // $payload = [
        //     'title' => 'Test Expense',
        //     'amount' => 100.00,
        //     'category' => 'food',
        //     'expense_date' => now()->toDateString(),
        //     'notes' => 'This is a test',
        // ];

        $payload = Expense::factory()->make([
            'id' => Uuid::uuid4()->toString(),
        ])->toArray();

        $response = $this->postJson('/api/expenses', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => $payload['title'],
                // 'amount' => number_format($payload['amount'], 2),
                'amount' => $payload['amount'], // keep it float
                'category' => $payload['category'],
            ]);

        $this->assertDatabaseHas('expenses', [
            'title' => $payload['title'],
            'amount' => $payload['amount'],
            'category' => $payload['category'],
        ]);
    }

    #[Test]
    public function test_expense_validation_fails_with_missing_fields(): void
    {
        $response = $this->postJson('/api/expenses', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'amount', 'category', 'expense_date']);
    }

    #[Test]
    public function test_user_can_list_expenses(): void
    {
        Expense::factory()->count(3)->create();

        $response = $this->getJson('/api/expenses');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function test_user_can_update_expense(): void
    {
        $expense = Expense::factory()->create();

        $update = [
            'title' => 'Updated Title',
            'amount' => 300,
            'category' => 'other',
            'expense_date' => now()->format('Y-m-d'),
            'notes' => 'Updated note',
        ];

        $response = $this->putJson("/api/expenses/{$expense->id}", $update);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('expenses', ['title' => 'Updated Title']);
    }

    #[Test]
    public function test_user_can_delete_expense(): void
    {
        $expense = Expense::factory()->create();

        $response = $this->deleteJson("/api/expenses/{$expense->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
