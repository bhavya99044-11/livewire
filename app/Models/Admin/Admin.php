<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_permissions');
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('slug', $permission);
    }

    public function hasAccess($permission){
        return $this->permissions()->where('module',$permission)->exists();
    }
}
