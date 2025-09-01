<?php

use Illuminate\Support\Facades\Route;
use Modules\Expenses\Http\Controllers\Api\ExpenseController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('expenses', ExpensesController::class)->names('expenses');
// });

Route::prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'index']);
    Route::post('/', [ExpenseController::class, 'store']);
    Route::put('/{expense}', [ExpenseController::class, 'update']);
    Route::delete('/{expense}', [ExpenseController::class, 'destroy']);
});
