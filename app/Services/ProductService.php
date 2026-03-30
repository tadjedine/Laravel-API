<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
// use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{
    public function __construct(private ProductRepository $productRepository) {}


    public function getProducts(array $filters = [])
    {
        return $this->productRepository->getAll($filters);
    }

    public function getProductById(int $id): Product
    {
        $product = $this->productRepository->getById($id);
        // if (! $product) {
        //     throw new ModelNotFoundException();
        // }
        // No need to check the product's existence here, we check in the repo
        //$product->load(['images', 'attributes', 'reviews']);
        $product->load(['images']);
        return $product;
    }

    public function isInStock(int $productId, int $quantity = 1): bool
    {
        $product = $this->productRepository->getById($productId);
        return ($product->quantity ?? 0) >= $quantity;
    }

    public function getProductsByCategory(int $categoryId, array $filters = [])
    {
        // we verify first the category existence
        $category = Category::findOrFail($categoryId);
        
        return $this->productRepository->getByCategory($categoryId, $filters);
    }

    public function searchProducts(string $query, array $filters = [])
    {
        return $this->productRepository->search($query, $filters);
    }
    
}