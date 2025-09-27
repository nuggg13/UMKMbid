<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'description',
        'business_license',
        'documents',
        'annual_revenue',
        'employee_count',
        'established_date',
        'status',
        'website',
        'social_media',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'annual_revenue' => 'decimal:2',
            'established_date' => 'date',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    public function activeAuctions()
    {
        return $this->auctions()->where('status', 'active');
    }

    // Helper methods
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
