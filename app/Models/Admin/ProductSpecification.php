<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table='product_specifications';

    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function subProduct(){
        return $this->belongsTo(SubProduct::class,'sub_product_id');
    }

    public function images(){
        return $this->hasMany(ProductImage::class,'product_specification_id');
    }
}
