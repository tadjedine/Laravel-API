<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository{

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        // First check cache, then PrestaShop
        // return cache()->remember(
        //     'products_' . md5(json_encode($filters)),
        //     now()->addHours(2),
        //     fn() => $this->prestaShopService->getProducts($filters)
        // );
        $query = Product::query();

        if (isset($filters['category'])) {
            $query->where('id_category_default', $filters['category']);
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($filters['per_page'] ?? 15);        
    }
    
    public function getById(int $id)
    {
        return Product::findOrFail($id);

    }

     public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->getById($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        $product = $this->getById($id);
        
        return $product->delete();
    }

    public function getByCategory(int $categoryId, array $filters = []): LengthAwarePaginator
    {
    
        return Product::where('id_category_default', $categoryId)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        // return Product::where('name', 'like', '%' . $query . '%')
        //     ->orWhere('description', 'like', '%' . $query . '%')
        //     ->paginate($filters['per_page'] ?? 15);
        
        return Product::where(function($q) use ($query) {
            $q->where('name', 'like', '%'.$query.'%')
         ->orWhere('description', 'like', '%'.$query.'%');
            })->where('active', 1);
    }


}