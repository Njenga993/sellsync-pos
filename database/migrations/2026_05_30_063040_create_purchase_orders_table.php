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
    Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id')->index();
        $table->unsignedBigInteger('supplier_id')->index();
        $table->unsignedBigInteger('user_id')->index();
        $table->string('po_number')->index();
        $table->string('status')->default('draft');
        $table->date('order_date');
        $table->date('expected_date')->nullable();
        $table->date('received_date')->nullable();
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
