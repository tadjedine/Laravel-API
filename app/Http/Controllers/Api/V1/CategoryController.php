<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $categoryService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'per_page']);
        $categories = $this->categoryService->getCategories($filters);
        
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        
        $category = $this->categoryService->createCategory($validated);
        
        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $category = $this->categoryService->getCategoryWithProducts($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = $this->categoryService->getCategoryById($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validated = $request->validated();
        $updated = $this->categoryService->updateCategory($id, $validated);

        return new CategoryResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = $this->categoryService->getCategoryById($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $this->categoryService->deleteCategory($id);

        return response()->noContent();
    }

    public function root(Request $request)
    {
        $filters = $request->only(['per_page']);
        $categories = $this->categoryService->getRootCategories($filters);

        return CategoryResource::collection($categories);
    }

    public function hierarchy(Request $request)
    {
        $parentId = $request->query('parent_id');
        $hierarchy = $this->categoryService->getCategoryHierarchy($parentId);

        return response()->json($hierarchy);
    }
}
