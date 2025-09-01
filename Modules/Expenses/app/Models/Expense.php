<?php

namespace Modules\Expenses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Expenses\Database\Factories\ExpenseFactory;

class Expense extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return ExpenseFactory::new();
    }

    protected $fillable = ['title', 'amount', 'category', 'expense_date', 'notes'];
}
