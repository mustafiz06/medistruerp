<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function getReturnedQuantityAttribute()
    {
        return $this->purchaseOrder->returns
            ->where('product_id', $this->product_id)
            ->sum('quantity');
    }

    public function getAvailableToReturnAttribute()
    {
        return max(0, $this->quantity - $this->returned_quantity);
    }
}
