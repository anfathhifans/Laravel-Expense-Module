<?php

namespace Modules\Expenses\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Expenses\Models\Expense;

class ExpenseNotification extends Notification
{
    use Queueable;

    public function __construct(public Expense $expense) {}

    public function via($notifiable): array {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage {
        return new DatabaseMessage([
            'title' => $this->expense->title,
            'amount' => $this->expense->amount,
            'expense_date' => $this->expense->expense_date,
        ]);
    }
}