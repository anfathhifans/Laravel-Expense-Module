<?php

namespace Modules\Expenses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */

    public function rules(): array {
        return [
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the body parameters for API documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'The title of the expense',
                'example' => 'Office Supplies'
            ],
            'amount' => [
                'description' => 'The amount of the expense',
                'example' => 150.75
            ],
            'category' => [
                'description' => 'The category of the expense',
                'example' => 'office'
            ],
            'expense_date' => [
                'description' => 'The date when the expense occurred',
                'example' => '2024-01-15'
            ],
            'notes' => [
                'description' => 'Additional notes about the expense (optional)',
                'example' => 'Purchased printer paper and pens'
            ],
        ];
    }
}
