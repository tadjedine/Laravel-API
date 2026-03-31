<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository{

    public function getAll(array $filters = []): LengthAwarePaginator
        {
        // First check cache, then PrestaShop
        // return cache()->remember(
        //     'products_' . md5(json_encode($filters)),
        //     now()->addHours(2),
        //     fn() => $this->prestaShopService->getProducts($filters)
        // );
        $query = Category::query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($filters['per_page'] ?? 15);        
    }
    
    public function getById(int $id)
    {
        return Category::findOrFail($id);

    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        return Category::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->where('active', 1)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getRootCategories(array $filters = []): LengthAwarePaginator
    {
        return Category::where('is_root_category', true)
            ->where('active', 1)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getHierarchy(int $parentId = null): array
    {
        $query = Category::where('active', 1);

        if ($parentId !== null) {
            $query->where('id_parent', $parentId);
        } else {
            $query->where('is_root_category', true);
        }

        $categories = $query->get();

        return $categories->map(function ($category) {
            return [
                'id' => $category->id_category,
                'name' => $category->name ?? 'Unnamed',
                'children' => $this->getHierarchy($category->id_category),
            ];
        })->toArray();
    }

    public function getHierarchyWithProducts(int $parentId = null): array
    {
    $query = Category::where('active', 1);

    if ($parentId !== null) {
        $query->where('id_parent', $parentId);
    } else {
        $query->where('is_root_category', true);
    }

    $categories = $query->with('products')->get(); // Load products!

    return $categories->map(function ($category) {
        return [
            'id' => $category->id_category,
            'name' => $category->name ?? 'Unnamed',
            'products' => $category->products,
            'children' => $this->getHierarchyWithProducts($category->id_category),
        ];
    })->toArray();
    }

}