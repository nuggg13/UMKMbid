<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business;
use App\Models\Auction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample UMKM owners
        $umkmOwner1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@umkm1.com',
            'password' => Hash::make('password'),
            'role' => 'umkm_owner',
            'phone' => '081234567890',
            'is_verified' => true,
        ]);

        $umkmOwner2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari@umkm2.com',
            'password' => Hash::make('password'),
            'role' => 'umkm_owner',
            'phone' => '081234567891',
            'is_verified' => true,
        ]);

        $umkmOwner3 = User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'ahmad@umkm3.com',
            'password' => Hash::make('password'),
            'role' => 'umkm_owner',
            'phone' => '081234567892',
            'is_verified' => true,
        ]);

        // Create sample investors
        $investor1 = User::create([
            'name' => 'Eko Investor',
            'email' => 'eko@investor.com',
            'password' => Hash::make('password'),
            'role' => 'investor',
            'phone' => '081234567893',
            'is_verified' => true,
        ]);

        $investor2 = User::create([
            'name' => 'Maya Capital',
            'email' => 'maya@investor.com',
            'password' => Hash::make('password'),
            'role' => 'investor',
            'phone' => '081234567894',
            'is_verified' => true,
        ]);

        // Create admin
        $admin = User::create([
            'name' => 'Admin UMKMBid',
            'email' => 'admin@umkmbid.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567895',
            'is_verified' => true,
        ]);

        // Create sample businesses
        $business1 = Business::create([
            'user_id' => $umkmOwner1->id,
            'name' => 'Warung Kopi Nusantara',
            'category' => 'fb',
            'description' => 'Ekspansi jaringan warung kopi dengan konsep kearifan lokal. Target pembukaan 10 cabang baru di Jakarta dan sekitarnya dalam 2 tahun.',
            'annual_revenue' => 500000000,
            'employee_count' => 15,
            'established_date' => '2020-01-15',
            'status' => 'approved',
        ]);

        $business2 = Business::create([
            'user_id' => $umkmOwner2->id,
            'name' => 'Toko Online Kerajinan Bali',
            'category' => 'ecommerce',
            'description' => 'Platform e-commerce khusus produk kerajinan tangan Bali. Sudah memiliki 500+ pengrajin dan target ekspor ke Asia Tenggara.',
            'annual_revenue' => 750000000,
            'employee_count' => 25,
            'established_date' => '2019-06-20',
            'status' => 'approved',
        ]);

        $business3 = Business::create([
            'user_id' => $umkmOwner3->id,
            'name' => 'Startup EdTech Lokal',
            'category' => 'tech',
            'description' => 'Platform pembelajaran online fokus kurikulum Indonesia. Sudah memiliki 10,000+ pengguna aktif dan kerjasama dengan 100+ sekolah.',
            'annual_revenue' => 1200000000,
            'employee_count' => 35,
            'established_date' => '2021-03-10',
            'status' => 'approved',
        ]);

        // Create sample auctions
        $auction1 = Auction::create([
            'business_id' => $business1->id,
            'title' => 'Ekspansi Warung Kopi Nusantara',
            'description' => 'Kami mencari investor untuk ekspansi jaringan warung kopi dengan konsep kearifan lokal. Modal akan digunakan untuk membuka 10 cabang baru di area strategis Jakarta dan sekitarnya.',
            'funding_goal' => 500000000,
            'equity_percentage' => 25.00,
            'minimum_bid' => 500000000,
            'current_highest_bid' => 520000000,
            'current_highest_bidder_id' => $investor1->id,
            'start_date' => now()->subHours(2),
            'end_date' => now()->addHours(22),
            'status' => 'active',
        ]);

        $auction2 = Auction::create([
            'business_id' => $business2->id,
            'title' => 'Ekspansi Platform E-commerce Kerajinan',
            'description' => 'Mengembangkan platform e-commerce kerajinan Bali untuk ekspor internasional. Dana akan digunakan untuk pengembangan teknologi dan marketing ke pasar Asia Tenggara.',
            'funding_goal' => 750000000,
            'equity_percentage' => 20.00,
            'minimum_bid' => 750000000,
            'current_highest_bid' => 780000000,
            'current_highest_bidder_id' => $investor2->id,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addMinutes(45),
            'status' => 'active',
        ]);

        $auction3 = Auction::create([
            'business_id' => $business3->id,
            'title' => 'Pengembangan Platform EdTech Indonesia',
            'description' => 'Memperluas platform pembelajaran online untuk mencakup seluruh Indonesia. Investasi untuk pengembangan fitur AI, konten berkualitas, dan infrastruktur yang scalable.',
            'funding_goal' => 1000000000,
            'equity_percentage' => 15.00,
            'minimum_bid' => 1000000000,
            'current_highest_bid' => 1100000000,
            'current_highest_bidder_id' => $investor1->id,
            'start_date' => now()->subHours(5),
            'end_date' => now()->addDays(1)->addHours(5),
            'status' => 'active',
        ]);

        // Create some bid records
        $auction1->bids()->create([
            'user_id' => $investor1->id,
            'amount' => 520000000,
            'message' => 'Sangat tertarik dengan konsep kearifan lokal yang ditawarkan.',
            'status' => 'active',
            'bid_time' => now()->subMinutes(30),
        ]);

        $auction2->bids()->create([
            'user_id' => $investor2->id,
            'amount' => 780000000,
            'message' => 'Potensi ekspor ke Asia Tenggara sangat menjanjikan.',
            'status' => 'active',
            'bid_time' => now()->subMinutes(15),
        ]);

        $auction3->bids()->create([
            'user_id' => $investor1->id,
            'amount' => 1100000000,
            'message' => 'Platform edukasi sangat diperlukan di Indonesia.',
            'status' => 'active',
            'bid_time' => now()->subHours(1),
        ]);
    }
}