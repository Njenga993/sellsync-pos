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
    Schema::create('business_settings', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id')->unique()->index();
        $table->string('logo_path')->nullable();
        $table->text('receipt_header')->nullable();
        $table->text('receipt_footer')->nullable();
        $table->boolean('receipt_show_logo')->default(true);
        $table->boolean('receipt_show_address')->default(true);
        $table->boolean('receipt_show_phone')->default(true);
        $table->boolean('receipt_show_email')->default(true);
        $table->boolean('receipt_show_tax')->default(true);
        $table->boolean('receipt_show_loyalty')->default(true);
        $table->string('currency_symbol')->default('KES');
        $table->string('currency_code')->default('KES');
        $table->string('tax_name')->default('VAT');
        $table->string('tax_number')->nullable();
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('website')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
