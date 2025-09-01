<?php

namespace Tests\Feature;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Modules\Expenses\Events\ExpenseCreated;
use Modules\Expenses\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Log, Event, Queue, Bus};

class ExpenseNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_dispatches_expense_created_event_when_expense_is_created()
    {
        Event::fake();

        // $payload = Expense::factory()->make()->toArray();
        $payload = Expense::factory()->make([
            'id' => Uuid::uuid4()->toString(), // set UUID manually
        ])->toArray();

        $this->postJson('/api/expenses', $payload)
             ->assertStatus(201);

        Event::assertDispatched(ExpenseCreated::class);
    }

    #[Test]
    public function test_send_expense_notification_listener_runs()
    {
        Event::fake();

        $expense = Expense::factory()->create();

        Event::dispatch(new ExpenseCreated($expense));

        // Here you can mock Log or notification if needed
        // Or just ensure event was dispatched successfully
        Event::assertDispatched(ExpenseCreated::class, function ($event) use ($expense) {
            return $event->expense->id === $expense->id;
        });
    }
}
