<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function getCategories(array $filters = [])
    {
        return $this->categoryRepository->getAll($filters);

    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->getById($id);
    }

    public function getCategoryWithProducts(int $id): ?Category
    {
        $category = $this->categoryRepository->getById($id);
        if ($category) {
            $category->load(['products', 'children']);
        }
        return $category;
    }

    public function getRootCategories(array $filters = [])
    {
        return $this->categoryRepository->getRootCategories($filters);
    }

    public function searchCategories(string $query, array $filters = [])
    {
        return $this->categoryRepository->search($query, $filters);
    }

    public function getCategoryHierarchyWithProducts(int $id): array
    {
    // Get the category
    $category = $this->categoryRepository->getById($id);
    
    if (!$category) {
        throw new ModelNotFoundException();
    }
    
    // Get full hierarchy with products starting from this category
    return $this->categoryRepository->getHierarchyWithProducts($category->id_category);
    }

    public function getCategoryHierarchy(int $parentId = null)
    {
        return $this->categoryRepository->getHierarchy($parentId);
    }

     public function createCategory(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function updateCategory(int $id, array $data): Category
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}