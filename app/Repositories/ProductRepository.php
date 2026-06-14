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
        $query = Product::with(['lang', 'coverImage', 'stockAvailable']);

        if (isset($filters['category'])) {
            $query->where('id_category_default', $filters['category']);
        }

        if (isset($filters['category_slug'])) {
            $query->whereHas('defaultCategory.lang', function ($q) use ($filters) {
                $q->where('link_rewrite', $filters['category_slug']);
            });
        }

        if (isset($filters['search'])) {
            $query->whereHas('lang', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Price filtering
        if (isset($filters['price_min'])) {
            $query->where('price', '>=', (float) $filters['price_min']);
        }
        if (isset($filters['price_max'])) {
            $query->where('price', '<=', (float) $filters['price_max']);
        }

        // Attribute filtering (e.g. ?attributes[1]=1,2)
        if (!empty($filters['attributes']) && is_array($filters['attributes'])) {
            foreach ($filters['attributes'] as $groupId => $valueIds) {
                // valueIds could be a comma separated string from query params
                $ids = is_string($valueIds) ? explode(',', $valueIds) : (array) $valueIds;
                
                $query->whereHas('productAttribute.attributes', function ($q) use ($groupId, $ids) {
                    $q->where('ps_attribute.id_attribute_group', $groupId)
                      ->whereIn('ps_attribute.id_attribute', $ids);
                });
            }
        }

        // Feature filtering (e.g. ?features[1]=1,3)
        if (!empty($filters['features']) && is_array($filters['features'])) {
            foreach ($filters['features'] as $featureId => $valueIds) {
                $ids = is_string($valueIds) ? explode(',', $valueIds) : (array) $valueIds;
                
                $query->whereHas('features', function ($q) use ($featureId, $ids) {
                    $q->where('ps_feature_product.id_feature', $featureId)
                      ->whereIn('ps_feature_product.id_feature_value', $ids);
                });
            }
        }

        $query->where('active', 1);

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->join('ps_product_lang', function($join) {
                    $join->on('ps_product.id_product', '=', 'ps_product_lang.id_product')
                         ->where('ps_product_lang.id_lang', 1);
                })
                ->orderBy('ps_product_lang.name', 'asc')
                ->select('ps_product.*');
                break;
            case 'newest':
            default:
                $query->orderBy('date_add', 'desc');
                break;
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }
    
    public function getById(int $id)
    {
        return Product::with(['lang', 'coverImage', 'stockAvailable'])->findOrFail($id);
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

    public function deleteProduct(int $id): bool
    {
        $product = $this->getById($id);
        
        return $product->delete($product->id_product);
    }

    public function getByCategory(int $categoryId, array $filters = []): LengthAwarePaginator
    {
        return Product::with(['lang', 'coverImage', 'stockAvailable'])
            ->where('id_category_default', $categoryId)
            ->where('active', 1)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        // return Product::where('name', 'like', '%' . $query . '%')
        //     ->orWhere('description', 'like', '%' . $query . '%')
        //     ->paginate($filters['per_page'] ?? 15);
        
        return Product::with(['lang', 'coverImage', 'stockAvailable'])
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%')
                  ->orWhere('description', 'like', '%'.$query.'%');
            })
            ->where('active', 1)
            ->paginate($filters['per_page'] ?? 15);
    }


}