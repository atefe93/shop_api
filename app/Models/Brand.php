<?php

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected static function newFactory()
    {
        return BrandFactory::new();
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
