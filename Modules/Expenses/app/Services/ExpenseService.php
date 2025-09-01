<?php

namespace Modules\Expenses\Services;

use Modules\Expenses\Models\Expense;
use Modules\Expenses\Events\ExpenseCreated;

class ExpenseService
{
    public function create(array $data): Expense {
        $expense = Expense::create($data);
        event(new ExpenseCreated($expense));
        return $expense;
    }

    public function update(Expense $expense, array $data): Expense {
        $expense->update($data);
        return $expense;
    }

    public function delete(Expense $expense): void {
        $expense->delete();
    }

    // public function list(?string $category = null, ?array $dateRange = null)
    public function list(?string $category = null, ?string $startDate = null, ?string $endDate = null)
    {
        $query = Expense::query();

        if ($category) {
            $query->where('category', $category);
        }

        // if ($dateRange) {
        //     $query->whereBetween('expense_date', $dateRange);
        // }

        if ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('expense_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('expense_date', '<=', $endDate);
        }

        return $query->get();
    }
}
