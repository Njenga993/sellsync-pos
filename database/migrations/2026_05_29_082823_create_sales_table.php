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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id')->index();
        $table->unsignedBigInteger('branch_id')->index();
        $table->unsignedBigInteger('user_id')->index();
        $table->unsignedBigInteger('customer_id')->nullable()->index();
        $table->string('invoice_no')->index();
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
        $table->decimal('discount_amount', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->decimal('amount_paid', 10, 2)->default(0);
        $table->decimal('change_amount', 10, 2)->default(0);
        $table->string('payment_method')->default('cash');
        $table->string('payment_status')->default('paid');
        $table->text('notes')->nullable();
        $table->string('status')->default('completed');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
