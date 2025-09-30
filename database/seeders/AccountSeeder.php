<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounting\Account;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // Assets
            ['name' => 'Cash', 'type' => 'Asset', 'normal_balance' => 'debit'],
            ['name' => 'Accounts Receivable', 'type' => 'Asset', 'normal_balance' => 'debit'],
            ['name' => 'Supplies', 'type' => 'Asset', 'normal_balance' => 'debit'],
            ['name' => 'Equipment', 'type' => 'Asset', 'normal_balance' => 'debit'],
            ['name' => 'Furniture', 'type' => 'Asset', 'normal_balance' => 'debit'],
            ['name' => 'Land', 'type' => 'Asset', 'normal_balance' => 'debit'],

            // Liabilities
            ['name' => 'Accounts Payable', 'type' => 'Liability', 'normal_balance' => 'credit'],
            ['name' => 'Notes Payable', 'type' => 'Liability', 'normal_balance' => 'credit'],

            // Equity
            ['name' => 'Owner’s Capital', 'type' => 'Equity', 'normal_balance' => 'credit'],
            ['name' => 'Owner’s Drawings', 'type' => 'Equity', 'normal_balance' => 'debit'],

            // Revenue
            ['name' => 'Service Revenue', 'type' => 'Revenue', 'normal_balance' => 'credit'],

            // Expenses
            ['name' => 'Rent Expense', 'type' => 'Expense', 'normal_balance' => 'debit'],
            ['name' => 'Utilities Expense', 'type' => 'Expense', 'normal_balance' => 'debit'],
            ['name' => 'Salaries Expense', 'type' => 'Expense', 'normal_balance' => 'debit'],
            ['name' => 'Supplies Expense', 'type' => 'Expense', 'normal_balance' => 'debit'],
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(
                ['name' => $account['name']], // avoid duplicates
                [
                    'type' => $account['type'],
                    'normal_balance' => $account['normal_balance'],
                    'description' => $account['type'] . ' account: ' . $account['name'],
                ]
            );
        }
    }
}
