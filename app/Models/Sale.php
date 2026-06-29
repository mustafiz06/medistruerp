<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who created the sale
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get sale items
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return "{$prefix}-{$date}-" . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'success',
            'partial' => 'warning',
            'due' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check if sale is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'paid';
    }


    

}
