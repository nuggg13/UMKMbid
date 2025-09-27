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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Investor who made the bid
            $table->decimal('amount', 15, 2); // Jumlah bid
            $table->text('message')->nullable(); // Pesan dari investor
            $table->enum('status', ['active', 'outbid', 'winning', 'won', 'lost'])->default('active');
            $table->timestamp('bid_time')->useCurrent();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['auction_id', 'amount']);
            $table->index(['user_id', 'bid_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
