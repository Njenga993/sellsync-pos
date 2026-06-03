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
    Schema::create('sale_return_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_return_id')->constrained()->onDelete('cascade');
        $table->unsignedBigInteger('sale_item_id')->nullable()->index();
        $table->unsignedBigInteger('product_id')->index();
        $table->string('product_name');
        $table->integer('qty')->default(1);
        $table->decimal('unit_price', 10, 2)->default(0);
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->boolean('restock')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_return_items');
    }
};
