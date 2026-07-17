<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PurchaseTransaction', function (Blueprint $table) {
            $table->string('transactionID', 50)->primary();
            $table->string('customerID', 50)->nullable()->index();
            $table->string('cashierID', 50)->nullable()->index();
            $table->date('transactionDate');
            $table->decimal('totalPrice', 10, 2);
            $table->integer('pointEarned')->nullable()->default(0);

            $table->foreign('customerID')->references('customerID')->on('Customer')->onDelete('set null');
            $table->foreign('cashierID')->references('cashierID')->on('Cashier')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PurchaseTransaction');
    }
};
