<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='products';
    protected $guarded = [];  

    public function images(){
        return $this->hasMany(ProductImage::class,'product_id');
    }

  

    public function subProducts(){
        return $this->hasMany(SubProduct::class,'product_id');
    }

    public function approve(){
        return $this->belongsTo(Admin::class,'approved_by');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
    public function items(){
        return $this->hasMany(ProductItem::class,'product_id');
    }

}
