<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Each referral now stores two rows: one for the customer whose code was used
     * ('referrer') and one for the new customer who used it ('referred').
     */
    public function up(): void
    {
        Schema::table('Referral', function (Blueprint $table) {
            $table->string('referralType', 20)->default('referrer')->after('dateRef');
        });
    }

    public function down(): void
    {
        Schema::table('Referral', function (Blueprint $table) {
            $table->dropColumn('referralType');
        });
    }
};
