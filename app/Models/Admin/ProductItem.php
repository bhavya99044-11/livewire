<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    protected $table='product_items';
    protected $guarded = [];  

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function subProduct(){
        return $this->belongsTo(SubProduct::class,'sub_product_id');
    }

   






}
