<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory , HasTranslations , SoftDeletes;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'image',
        'name',
        'model',
        'sku',
        'quantity',
        'price',
        'discount_price',
        'cost_price',
        'rate',
        'slug',
        'keyword',
        'meta_title',
        'meta_description',
        'product_tag',
        'status',
        'in_stock',
        'limited_inStock',
        'width',
        'height',
        'weight',
        'length',
        'description',
    ];

    public function scopeStatus(Builder $query,$status='active'){
        $query->where('status','=',$status);

    }
}
