<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table='faqs';

    protected $fillable = [
        'question',
        'answer',
        'order_id'
        // add any other fields that you want to allow for mass assignment
    ];}
