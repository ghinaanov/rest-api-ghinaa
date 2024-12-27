<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Field yang dapat diisi
    protected $fillable = ['name', 'description', 'price', 'image'];
}

