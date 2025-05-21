<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SubProduct extends Model
{
    protected $table='sub_products';

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function items(){
        return $this->hasMany(ProductItem::class,'sub_product_id');
    }

    public function images(){
        return $this->hasMany(ProductImage::class,'sub_product_id');
    }

    public function specs(){
        return $this->hasMany(ProductSpecification::class,'sub_product_id');
    }
}
