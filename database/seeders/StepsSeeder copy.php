<?php
   use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::insert([
            [
                'name' => 'الباقة البرونزية',
                'from' => 0.00,
                'to' => 5000.00,
                'fee' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الباقة الفضية',
                'from' => 5000.00,
                'to' => 10000.00,
                'fee' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الباقة الذهبية',
                'from' => 10000.00,
                'to' => 1000000.00,
                'fee' => 20.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
