<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['method_name' => 'Cash'],
            ['method_name' => 'Card'],
            ['method_name' => 'Bank transfer'],
            ['method_name' => 'Mobile money'],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['method_name' => $method['method_name']],
                $method
            );
        }
    }
}
