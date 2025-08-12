<?php

namespace App\Domains\Shop\Catalog\Repositories;

use App\Domains\Shared\Product\Models\Product;  

class ProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Get active products for catalog display
     */
    public function getActiveProducts($perPage = 15, $filters = [])
    {
        $query = $this->model->where('is_active', true)
                            ->where('stock', '>', 0);
        
        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }
        
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }
        
        return $perPage === 'all' ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Find active product by slug
     */
    public function findActiveBySlug($slug)
    {
        return $this->model->where('slug', $slug)
                          ->where('is_active', true)
                          ->where('stock', '>', 0)
                          ->first();
    }

    /**
     * Find active product by ID
     */
    public function findActiveById($id)
    {
        return $this->model->where('id', $id)
                          ->where('is_active', true)
                          ->where('stock', '>', 0)
                          ->first();
    }

    /**
     * Check product availability
     */
    public function checkAvailability($productId, $quantity)
    {
        $product = $this->model->find($productId);
        
        if (!$product || !$product->is_active) {
            return false;
        }
        
        return $product->stock >= $quantity;
    }

    /**
     * Reduce stock after order
     */
    public function reduceStock($productId, $quantity)
    {
        return $this->model->where('id', $productId)
                          ->decrement('stock', $quantity);
    }

    /**
     * Restore stock when order cancelled
     */
    public function restoreStock($productId, $quantity)
    {
        return $this->model->where('id', $productId)
                          ->increment('stock', $quantity);
    }
}