<?php

namespace Modules\Expenses\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Expenses\Http\Requests\{StoreExpenseRequest, UpdateExpenseRequest};
use Modules\Expenses\Models\Expense;
use Modules\Expenses\Services\ExpenseService;
use Illuminate\Http\{Request, Response, JsonResponse};
use Modules\Expenses\Resources\{ExpenseResource, ExpenseCollection};

class ExpenseController extends Controller
{
    public function __construct(private ExpenseService $service) {}

    /**
     * List all expenses
     *
     * @queryParam category string Filter by category. Example: travel
     * @queryParam start_date date Filter start (YYYY-MM-DD). Example: 2025-01-01
     * @queryParam end_date date Filter end (YYYY-MM-DD). Example: 2025-12-31
     *
     * @response 200 scenario="Success" [
     *     {
     *       "id": "uuid",
     *       "title": "Lunch",
     *       "amount": 45.50,
     *       "category": "food",
     *       "expense_date": "2025-07-01",
     *       "notes": "Client meeting"
     *     }
     * ]
     */
    public function index(Request $request): JsonResponse
    {
        $category = $request->query('category');
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $expenses = $this->service->list($category, $start, $end);

        return response()->json(ExpenseResource::collection($expenses));
    }

    /**
     * Create an expense
     *
     * @bodyParam title string required Title of the expense. Example: Flight
     * @bodyParam amount decimal required Amount. Example: 123.45
     * @bodyParam category string required Category. Example: travel
     * @bodyParam expense_date date required Expense date. Example: 2025-07-01
     * @bodyParam notes string Notes (optional). Example: Business trip
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->service->create($request->validated());
        return response()->json(new ExpenseResource($expense), Response::HTTP_CREATED);
    }

    /**
     * Update an expense
     *
     * @urlParam id uuid required Expense UUID. Example: 3f0c...
     * @bodyParam title string Title of the expense. Example: Taxi
     * @bodyParam amount decimal Amount. Example: 20
     * @bodyParam category string Category. Example: transport
     * @bodyParam expense_date date Expense date. Example: 2025-07-02
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense = $this->service->update($expense, $request->validated());
        return response()->json(new ExpenseResource($expense));
    }

     /**
     * Delete an expense
     *
     * @urlParam id uuid required Expense UUID. Example: 3f0c...
     * @response 204 scenario="Deleted" null
     */
    public function destroy(Expense $expense): JsonResponse {
        $this->service->delete($expense);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
