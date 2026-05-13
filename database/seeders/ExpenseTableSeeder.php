<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExpenseTableSeeder extends Seeder {
    public array $expenses = [
        [
            "user_id"      => 1,
            "title"        => "Office Supplies",
            "amount"       => 150.00,
            "expense_date" => null,
        ],
        [
            "user_id"      => 1,
            "title"        => "Travel",
            "amount"       => 450.50,
            "expense_date" => null,
        ],
        [
            "user_id"      => 1,
            "title"        => "Lunch Meeting",
            "amount"       => 75.20,
            "expense_date" => null,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run() { 
        if (env('DEMO_MODE')) {
            foreach ($this->expenses as $expense) {
                $expense['expense_date'] = Carbon::now();
                Expense::create($expense);
            }
        }
    }
}