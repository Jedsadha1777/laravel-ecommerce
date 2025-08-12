<?php

namespace App\Domains\Shop\Catalog\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domains\Shop\Catalog\Services\CatalogService;
use App\Domains\Shop\Catalog\Resources\ProductResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(Request $request)
    {
        $products = $this->catalogService->getProducts($request);
        
        if ($request->has('page')) {
            return ApiResponse::paginated($products, 'Products retrieved successfully');
        }
        
        return ApiResponse::success(
            ProductResource::collection($products),
            'Products retrieved successfully'
        );
    }

    public function show($slug)
    {
        $product = $this->catalogService->getProductBySlug($slug);
        
        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }
        
        return ApiResponse::success(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }
}