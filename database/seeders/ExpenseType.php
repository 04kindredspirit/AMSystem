<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expense_types')->insert([
            ['name' => 'Utilities'],
            ['name' => 'Salaries'],
            ['name' => 'Petty Cash'],
            ['name' => 'Maintenance'],
            ['name' => 'Supplies'],
        ]);
    }
}