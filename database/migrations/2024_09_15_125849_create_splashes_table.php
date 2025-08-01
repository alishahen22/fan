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
        Schema::create('splashes', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title_ar');
            $table->string('title_en');
            $table->longText('desc_en');
            $table->longText('desc_ar');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('splashes');
    }
};
