<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class PrintSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('print_settings')->insert([
            [
                'key' => 'company_name',
                'value' => 'شركة فن للطباعة والنشر',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'key' => 'commercial_record',
                'value' => '30042336740000З',
                'created_at' => '2025-07-26 10:29:35',
                'updated_at' => null,
            ],
            [
                'key' => 'tax_number',
                'value' => '30042336740000З',
                'created_at' => '2025-07-26 10:29:40',
                'updated_at' => null,
            ],
            [
                'key' => 'phone',
                'value' => '0100 123 4567',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'key' => 'email',
                'value' => 'info@futureprint.com',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'key' => 'tax',
                'value' => '15',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'key' => 'tax_calculation_period',
                'value' => 'longTime',
                'created_at' => '2025-07-26 11:31:35',
                'updated_at' => null,
            ],
            [
                'key' => 'logo',
                'value' => '175350964868846f10564f2.png',
                'created_at' => '2025-07-26 09:00:48',
                'updated_at' => null,
            ],
            [
                'key' => 'address',
                'value' => 'الرياض',
                'created_at' => '2025-07-26 08:51:58',
                'updated_at' => null,
            ],
        ]);
    }
}