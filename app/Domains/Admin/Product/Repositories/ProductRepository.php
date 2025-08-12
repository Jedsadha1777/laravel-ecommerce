<?php

namespace App\Domains\Admin\Product\Repositories;

use App\Domains\Shared\Product\Models\Product;

class ProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll($perPage = 15, $search = null)
    {
        $query = $this->model->query();
        
        if ($search) {
            $query->search($search);
        }
        
        if ($perPage === 'all') {
            return $query->get();
        }
        
        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        
        if (!$product) {
            return null;
        }
        
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        
        if (!$product) {
            return false;
        }
        
        return $product->delete();
    }
}