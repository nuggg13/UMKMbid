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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('funding_goal', 15, 2); // Modal yang dibutuhkan
            $table->decimal('equity_percentage', 5, 2); // Persentase equity yang ditawarkan
            $table->decimal('minimum_bid', 15, 2)->nullable(); // Minimum bid
            $table->decimal('current_highest_bid', 15, 2)->default(0);
            $table->foreignId('current_highest_bidder_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['draft', 'active', 'ended', 'cancelled'])->default('draft');
            $table->json('terms_conditions')->nullable(); // Syarat dan ketentuan khusus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
