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
    Schema::create('branches', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id');
        $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        $table->string('name');
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->boolean('is_main')->default(false);
        $table->string('status')->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
