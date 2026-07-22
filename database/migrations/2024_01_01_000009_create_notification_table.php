<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Notification', function (Blueprint $table) {
            $table->string('notificationID', 50)->primary();
            $table->string('adminID', 50)->nullable()->index();
            $table->string('customerID', 50)->nullable()->index();
            $table->text('messageContent');
            $table->string('subject', 255)->nullable();
            $table->string('attachment', 255)->nullable();
            $table->string('filterValue', 50)->nullable();
            $table->string('filterType', 50)->nullable();
            $table->date('creationDate');
            $table->string('status', 50)->nullable();

            $table->foreign('customerID')->references('customerID')->on('Customer')->onDelete('cascade');
            $table->foreign('adminID')->references('adminID')->on('Admin')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Notification');
    }
};
