<?php

namespace App\Domains\Shop\Catalog\Services;

use App\Domains\Shop\Catalog\Repositories\ProductRepository;

class CatalogService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts($request)
    {
        $filters = [
            'search' => $request->get('search'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'sort' => $request->get('sort', 'name'),
        ];
        
        $perPage = $request->get('per_page', 15);
        
        return $this->productRepository->getActiveProducts($perPage, $filters);
    }

    public function getProductBySlug($slug)
    {
        return $this->productRepository->findActiveBySlug($slug);
    }

    public function checkProductAvailability($productId, $quantity)
    {
        return $this->productRepository->checkAvailability($productId, $quantity);
    }
}