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
    Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('from', 10, 2);
        $table->decimal('to', 10, 2);
        $table->decimal('fee', 5, 2)->comment('Fee percentage'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
