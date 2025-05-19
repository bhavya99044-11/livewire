<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ['name', 'url'];

    public function vendors(){
        return $this->belongsToMany(Vendor::class,'domain_vendors');
    }

}
