<?php

namespace Modules\Expenses\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Expenses\Events\ExpenseCreated;
use Modules\Expenses\Notifications\ExpenseNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendExpenseNotification implements ShouldQueue
{
    public function handle(ExpenseCreated $event): void
    {
        if (! $event->expense || ! $event->expense->id) {
            Log::warning('Expense object or ID is missing in ExpenseCreated event');
            return;
        }

        Log::info('Expense Created ID: ' . $event->expense->id);

        $user = User::first();

        if (! $user) {
            Log::warning('No user found to send notification');
            return;
        }

        // Notification::route('database', $user->id)->notify(new ExpenseNotification($event->expense));
        $user->notify(new ExpenseNotification($event->expense));
    }
}
