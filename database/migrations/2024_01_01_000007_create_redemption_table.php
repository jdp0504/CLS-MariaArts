<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Redemption', function (Blueprint $table) {
            $table->string('redemptionID', 50)->primary();
            $table->string('customerID', 50)->index();
            $table->string('rewardID', 50)->index();
            $table->date('redeemedDate');

            $table->foreign('customerID')->references('customerID')->on('Customer')->onDelete('cascade');
            $table->foreign('rewardID')->references('rewardID')->on('Reward')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Redemption');
    }
};
