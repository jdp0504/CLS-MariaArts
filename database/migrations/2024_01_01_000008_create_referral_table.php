<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Referral', function (Blueprint $table) {
            $table->string('referralID', 50)->primary();
            $table->string('customerID', 50)->index();
            $table->integer('pointGranted')->nullable()->default(0);
            $table->date('dateRef');

            $table->foreign('customerID')->references('customerID')->on('Customer')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Referral');
    }
};
