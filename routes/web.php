<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Modules\Expenses\Models\Expense;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('test', [TestController::class, 'index']);
// Route::get('test/{id}', [TestController::class, 'test']);

Route::controller(TestController::class)->prefix('test')->group(function() {
    Route::get('/', 'index');
    // Route::get('/{id}/{slug?}', 'test');
});

Route::get('/db/{id}', function (Expense $expense, $id) {
    // dd($expense->firstWhere('id', $id)->title);
    dd($expense->find($id));
});
