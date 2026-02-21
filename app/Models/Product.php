<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function origin()
    {
        return $this->belongsTo(Country::class, 'origin_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {

            do {
                $sku = rand(1000000000, 9999999999);
            } while (self::where('sku', $sku)->exists());

            $product->sku = $sku;

        });
    }
}
