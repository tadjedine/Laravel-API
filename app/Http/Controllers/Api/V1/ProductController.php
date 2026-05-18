<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductImageResource;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'category_slug', 'search', 'per_page']);
        $products = $this->productService->getProducts($filters);
        
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        
        $product = $this->productService->createProduct($validated);
        
        return response()->json(new ProductResource($product), 201);
    }

    public function show(int $id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validated();
        $updated = $this->productService->updateProduct($id, $validated);

        return new ProductResource($updated);
    }

    public function destroy(int $id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->productService->deleteProduct($id);

        return response()->noContent();
    }

    // Getting a single image by ID
    public function getImage(int $productId, int $imageId)
    {
        $image = $this->productService->getImage($productId, $imageId);

        // if (!$product) {
        //     return response()->json(['message' => 'Product not found'], 404);
        // }

        // $image = $product->images()->where('id_image', $imageId)->first();

        // if (!$image) {
        //     return response()->json(['message' => 'Image not found'], 404);
        // }

        return new ProductImageResource($image);
    }

    // Getting all images of a product
    public function getImages(int $id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return ProductImageResource::collection($product->images);
    }
}