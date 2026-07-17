<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Reward', function (Blueprint $table) {
            $table->string('rewardID', 50)->primary();
            $table->string('rewardName', 150);
            $table->text('description')->nullable();
            $table->integer('pointRequired');
            $table->integer('stock')->nullable()->default(0);
            $table->string('status', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Reward');
    }
};
