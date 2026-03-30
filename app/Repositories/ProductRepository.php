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

    public function getByCategory(int $categoryId, array $filters = []): LengthAwarePaginator
    {
    
        return Product::where('id_category_default', $categoryId)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        return Product::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->paginate($filters['per_page'] ?? 15);
    }


}