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
        Schema::create('quotations', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('number')->unique();
        $table->date('date');

        $table->string('tax_number')->nullable();
        $table->string('commercial_record')->nullable();

        // الحسابات
        $table->decimal('subtotal', 10, 2);
        $table->unsignedTinyInteger('management_fee_percentage')->nullable(); // مثال: 40
        $table->decimal('management_fee', 10, 2)->default(0);
        $table->unsignedTinyInteger('tax_percentage')->nullable(); // مثال: 15
        $table->decimal('tax', 10, 2)->default(0);
        $table->decimal('total', 10, 2);

        $table->enum('type', ['quotation', 'invoice'])->default('quotation');
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};