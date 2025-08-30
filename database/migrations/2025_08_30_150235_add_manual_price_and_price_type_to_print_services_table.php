<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('print_services', function (Blueprint $table) {
            $table->decimal('manual_price', 10, 2)->nullable();
            $table->string('price_type')->nullable()->after('manual_price'); // 'per_unit' or 'total'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_services', function (Blueprint $table) {
            //
        });
    }
};