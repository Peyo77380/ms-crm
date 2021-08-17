<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(20)->create();
         \App\Models\Company::factory(20)->create();
         \App\Models\CompanyHasUser::factory(20)->create();

        \App\Models\Task::factory(10)->create();

        \App\Models\Quote::factory(10)->create();
        \App\Models\QuoteDetail::factory(50)->create();

        \App\Models\Invoice::factory(5)->create();
        \App\Models\InvoiceDetail::factory(30)->create();
        \App\Models\InvoicePayment::factory(2)->create();

        \App\Models\VirtualMoney::factory(30)->create();
        \App\Models\VirtualMoneyExchange::factory(100)->create();

        \App\Models\LogAccounting::factory(30)->create();


    }
}
