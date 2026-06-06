<?php

namespace App\Models;

use Database\Factories\FoodFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Food extends Model
{
    /** @use HasFactory<FoodFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'food';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];
    protected $casts = ['deleted_at' => 'datetime'];
}
