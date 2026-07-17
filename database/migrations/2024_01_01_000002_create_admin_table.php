<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Admin', function (Blueprint $table) {
            $table->string('adminID', 50)->primary();
            $table->string('adminName', 150);

            $table->foreign('adminID')->references('userID')->on('User')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Admin');
    }
};
