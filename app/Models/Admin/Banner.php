<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table='banners';

    protected $attribute=[];

    protected $fillable=[
        'title',
        'banner',
        'status'
    ];

    protected $casts=[
        'status'=>'integer'
    ];
}
