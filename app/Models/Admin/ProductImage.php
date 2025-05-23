<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table='product_images';

    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function subProduct(){
        return $this->belongsTo(SubProduct::class,'sub_product_id');
    }
}
