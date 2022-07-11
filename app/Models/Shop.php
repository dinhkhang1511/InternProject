<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $table = 'shop';
    protected $fillable =  [
        'id',
        'name',
        'domain',
        'email',
        'shopify_domain',
        'access_token',
        'plan'
    ];

    protected $hidden =[
        'access_token',
    ];
}
