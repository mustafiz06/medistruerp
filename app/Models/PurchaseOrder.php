<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function returns()
    {
        return $this->hasMany(PurchaseReturn::class, 'purchase_order_id');
    }
    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }
    public function getReturnableQuantity($productId)
    {
        $item = $this->items->firstWhere('product_id', $productId);
        if (!$item) return 0;

        $returned = $this->returns->where('product_id', $productId)->sum('quantity');
        return max(0, $item->quantity - $returned);
    }

    public function isFullyReturned()
    {
        return $this->items->sum('quantity') <= $this->returns->sum('quantity');
    }

    public function isReturnable()
    {
        return $this->status === 'completed' && !$this->isFullyReturned();
    }

    public function getTotalReturnedAttribute()
    {
        return $this->returns->sum('total');
    }
}
