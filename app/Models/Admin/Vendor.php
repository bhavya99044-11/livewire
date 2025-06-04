<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'domain_id',
        'name',
        'email',
        'contact_number',
        'password',
        'shop_name',
        'image',
        'logo_url',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'status',
        'is_shop',
        'latitude',
        'longitude',
        'open_time',
        'close_time',
        'packaging_processing_charges',
        'is_approved',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_store' => 'boolean',
        'open_time' => 'datetime:H:i:s',
        'close_time' => 'datetime:H:i:s',
        'lattitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'packaging_processing_charges' => 'float',
    ];

    public function domains(){
        return $this->belongsToMany(Domain::class,'domain_vendors');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y');
    }
}
