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
    Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id')->index();
        $table->unsignedBigInteger('product_id')->index();
        $table->unsignedBigInteger('user_id')->index();
        $table->string('type'); // stock_in, stock_out, adjustment, sale, return
        $table->integer('qty'); // positive or negative
        $table->integer('before_qty')->default(0);
        $table->integer('after_qty')->default(0);
        $table->string('reference')->nullable(); // e.g. invoice no, PO number
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
