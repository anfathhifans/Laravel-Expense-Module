<?php

namespace Modules\Expenses\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Expenses\Models\Expense;

class ExpenseCreated
{
    use SerializesModels;

    public $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }
}
