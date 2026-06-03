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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id')->index();
        $table->unsignedBigInteger('category_id')->nullable()->index();
        $table->string('name');
        $table->string('sku')->nullable();
        $table->string('barcode')->nullable()->index();
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->decimal('cost_price', 10, 2)->default(0);
        $table->decimal('tax_rate', 5, 2)->default(0);
        $table->boolean('track_stock')->default(true);
        $table->integer('stock_qty')->default(0);
        $table->integer('low_stock_alert')->default(5);
        $table->string('status')->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
