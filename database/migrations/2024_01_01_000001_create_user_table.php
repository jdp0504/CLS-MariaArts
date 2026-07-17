<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('User', function (Blueprint $table) {
            $table->string('userID', 50)->primary();
            $table->string('username', 100)->unique();
            $table->string('role', 50);
            $table->date('createdDate');
            $table->string('password', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('User');
    }
};
