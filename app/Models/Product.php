<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'quantity',
        'status',
        'image',
        'shop_id'];

    protected $nullable = [
        'description',
        'price',
        'quantity',
        'status',
        'image'
    ];

    protected $observers = [
        Product::class => [ProductObserver::class],
    ];



}
