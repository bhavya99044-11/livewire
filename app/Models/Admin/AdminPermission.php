<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    protected $table = 'admin_permissions';

    protected $fillable = ['admin_id', 'permission_id'];
}
