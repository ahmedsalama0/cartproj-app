<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // one-to-many relation
    // a single product can has multiple imgs
    // a single product can has multiple colors
    public function colors() {
        return $this->hasMany(ProductColor::class);
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }
}
