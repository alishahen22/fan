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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('customer_type', ['individual', 'business'])->default('individual')->after('phone');
            $table->string('commercial_register')->nullable()->after('customer_type');
            $table->string('commercial_register_image')->nullable()->after('commercial_register');
            $table->string('tax_number')->nullable()->after('commercial_register_image');
            $table->string('tax_number_image')->nullable()->after('tax_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
              $table->dropColumn([
                'customer_type',
                'commercial_register',
                'commercial_register_image',
                'tax_number',
                'tax_number_image',
            ]);
        });
    }
};
