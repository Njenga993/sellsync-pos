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
    Schema::create('sale_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained()->onDelete('cascade');
        $table->unsignedBigInteger('product_id')->index();
        $table->unsignedBigInteger('variant_id')->nullable()->index();
        $table->string('product_name');
        $table->string('variant_name')->nullable();
        $table->integer('qty')->default(1);
        $table->decimal('unit_price', 10, 2)->default(0);
        $table->decimal('cost_price', 10, 2)->default(0);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
