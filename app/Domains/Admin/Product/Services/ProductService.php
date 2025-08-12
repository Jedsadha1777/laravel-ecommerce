<?php

namespace App\Domains\Admin\Product\Services;

use App\Domains\Admin\Product\Repositories\ProductRepository;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts($request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        
        return $this->productRepository->getAll($perPage, $search);
    }

    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data)
    {
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }
}