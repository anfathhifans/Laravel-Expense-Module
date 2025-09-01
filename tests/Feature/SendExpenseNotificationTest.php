<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Modules\Expenses\Events\ExpenseCreated;
use Modules\Expenses\Listeners\SendExpenseNotification;
use Modules\Expenses\Models\Expense;

class SendExpenseNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_listener_logs_expense_id()
    {
        Log::spy();

        $expense = Expense::factory()->create();

        $listener = new SendExpenseNotification();
        $listener->handle(new ExpenseCreated($expense));

        Log::shouldHaveReceived('info')->with("Expense Created ID: {$expense->id}");
    }
}
