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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
             $table->foreignId('quotation_id')->constrained()->onDelete('cascade');

            $table->string('description'); // وصف الصنف (مثلاً: كروت دعوة لحفل)
            $table->unsignedInteger('quantity');
            //supplies_ids
            $table->string('supplies_ids')->nullable(); 
            $table->decimal('price', 10, 2)->default(0);  // مجموع تكلفة المستلزمات
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
