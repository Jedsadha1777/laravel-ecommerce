<?php

namespace App\Domains\Admin\Product\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domains\Admin\Product\Services\ProductService;
use App\Domains\Admin\Product\Requests\CreateProductRequest;
use App\Domains\Admin\Product\Requests\UpdateProductRequest;
use App\Domains\Admin\Product\Resources\ProductResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;

    }

    public function index(Request $request)
    {
        $products = $this->productService->getAllProducts($request);
        
        if ($request->has('page')) {
            return ApiResponse::paginated($products, 'Products retrieved successfully');
        }
        
        return ApiResponse::success(
            ProductResource::collection($products),
            'Products retrieved successfully'
        );
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());
        
        return ApiResponse::success(
            new ProductResource($product),
            'Product created successfully',
            201
        );
    }

    public function show($id)
    {
        $product = $this->productService->getProductById($id);
        
        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }
        
        return ApiResponse::success(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productService->updateProduct($id, $request->validated());
        
        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }
        
        return ApiResponse::success(
            new ProductResource($product),
            'Product updated successfully'
        );
    }

    public function destroy($id)
    {
        $deleted = $this->productService->deleteProduct($id);
        
        if (!$deleted) {
            return ApiResponse::error('Product not found', 404);
        }
        
        return ApiResponse::success(null, 'Product deleted successfully');
    }
}