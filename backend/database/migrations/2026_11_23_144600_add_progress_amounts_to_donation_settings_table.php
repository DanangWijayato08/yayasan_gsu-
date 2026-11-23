<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('collected_amount')->default(850000000)->after('description');
            $table->unsignedBigInteger('target_amount')->default(1200000000)->after('collected_amount');
        });
    }

    public function down(): void
    {
        Schema::table('donation_settings', function (Blueprint $table) {
            $table->dropColumn(['collected_amount', 'target_amount']);
        });
    }
};
