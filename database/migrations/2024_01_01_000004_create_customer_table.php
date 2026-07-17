<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Customer', function (Blueprint $table) {
            $table->string('customerID', 50)->primary();
            $table->string('customerName', 150);
            $table->date('birthDate')->nullable();
            $table->string('phoneNumber', 20)->nullable();
            $table->string('referralCode', 50)->nullable();
            $table->integer('currentPoints')->nullable()->default(0);
            $table->string('email', 100)->nullable()->unique();
            $table->string('status', 50)->nullable();
            $table->timestamp('archivedAt')->nullable();

            $table->foreign('customerID')->references('userID')->on('User')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Customer');
    }
};
