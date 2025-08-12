<?php

namespace App\Domains\Shared\Product\Models;

use App\Traits\UsesUuid;
use App\Traits\KeywordSearchable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use UsesUuid, KeywordSearchable;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'sku',
        'is_active',
        'images',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    protected $searchableFields = ['name', 'sku', 'description'];
}