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
    Schema::create('z_reports', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id')->index();
        $table->unsignedBigInteger('branch_id')->index();
        $table->unsignedBigInteger('user_id')->index();
        $table->date('report_date')->index();
        $table->string('shift')->default('main');
        $table->string('status')->default('open');
        $table->decimal('opening_float', 10, 2)->default(0);
        $table->decimal('total_cash_sales', 10, 2)->default(0);
        $table->decimal('total_card_sales', 10, 2)->default(0);
        $table->decimal('total_mobile_sales', 10, 2)->default(0);
        $table->decimal('total_split_sales', 10, 2)->default(0);
        $table->decimal('total_sales', 10, 2)->default(0);
        $table->integer('total_transactions')->default(0);
        $table->decimal('total_refunds', 10, 2)->default(0);
        $table->decimal('total_cash_refunds', 10, 2)->default(0);
        $table->decimal('total_expenses', 10, 2)->default(0);
        $table->decimal('total_cash_expenses', 10, 2)->default(0);
        $table->decimal('expected_cash', 10, 2)->default(0);
        $table->decimal('actual_cash', 10, 2)->default(0);
        $table->decimal('cash_variance', 10, 2)->default(0);
        $table->text('notes')->nullable();
        $table->timestamp('closed_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('z_reports');
    }
};
