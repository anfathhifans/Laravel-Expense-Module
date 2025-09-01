<?php

namespace Modules\Expenses\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expenses\Models\Expense;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'category' => $this->faker->randomElement(['travel', 'food', 'office']),
            'expense_date' => $this->faker->date(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}