<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->decimal('equity_percentage', 5, 2)->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropColumn('equity_percentage');
        });
    }
};