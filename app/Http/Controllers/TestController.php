<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Expenses\Models\Expense;

class TestController extends Controller
{
    public function index() {
        echo 'test';
    }

    // public function test(Request $request, $id, $slug = 'test') {
        // dd(Expense::whereRaw('email = ?', [$request->input('email')])->get());
        // dd(Expense::whereRaw('id = ?', ['0197e821-6d39-712c-96e8-87a6db0e0264'])->get());
    //     dd($request, $id, $slug, $request->query('cat'), request('cat'));
    // }
}
