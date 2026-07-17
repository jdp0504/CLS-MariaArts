<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Cashier', function (Blueprint $table) {
            $table->string('cashierID', 50)->primary();
            $table->string('cashierName', 150);

            $table->foreign('cashierID')->references('userID')->on('User')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Cashier');
    }
};
