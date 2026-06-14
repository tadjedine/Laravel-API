<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockAvailable;
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

        $product->load([
            'images',
            'productAttribute.attributes.group.lang',
            'productAttribute.attributes.lang',
            'productAttribute.stockAvailable',
            'productAttribute.images',
        ]);

        return $product;
    }

    public function createProduct(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    public function updateProduct(int $id, array $data): Product
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct(int $id): void
    {
        $this->productRepository->delete($id);
    }

    public function isInStock(int $productId, int $idProductAttribute = 0 ,int $quantity = 1): bool
    {
        // $product = $this->productRepository->getById($productId);
        // return ($product->quantity ?? 0) >= $quantity;
        $product = $this->getProductById($productId);

        $stock = StockAvailable::query()
                    ->where('id_product', $product->id_product)
                    ->where('id_product_attribute', $idProductAttribute)
                    ->value('quantity');

        return ($stock >= $quantity);
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

    public function getImage(int $productId, int $imageId)
    {
        $product = $this->productRepository->getById($productId);

        $image = $product->images()->where('id_image', $imageId)->firstOrFail();

        return $image;
    }
    
}