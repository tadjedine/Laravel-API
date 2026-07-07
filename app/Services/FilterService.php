<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FilterService
{
    /**
     * Get available filters for a given set of product IDs (or all active products).
     *
     * @param array $filters  Optional filters like ['category_slug' => '...', 'category' => id]
     * @return array           ['attributes' => [...], 'features' => [...], 'price_range' => [...]]
     */
    public function getAvailableFilters(array $filters = []): array
    {
        // Build the base product IDs query
        $productQuery = DB::table('ps_product')
            ->where('ps_product.active', 1)
            ->select('ps_product.id_product');

        if (!empty($filters['category'])) {
            $productQuery->where('ps_product.id_category_default', $filters['category']);
        }

        if (!empty($filters['category_slug'])) {
            $productQuery->join('ps_category AS cat', 'ps_product.id_category_default', '=', 'cat.id_category')
                ->join('ps_category_lang AS catl', function ($join) {
                    $join->on('cat.id_category', '=', 'catl.id_category')
                         ->where('catl.id_lang', config('app.prestashop_lang', 1));
                })
                ->where('catl.link_rewrite', $filters['category_slug']);
        }

        $productIds = $productQuery->pluck('ps_product.id_product')->toArray();

        if (empty($productIds)) {
            return [
                'attributes' => [],
                'features' => [],
                'price_range' => ['min' => 0, 'max' => 0],
            ];
        }

        return [
            'attributes' => $this->getAttributeFilters($productIds),
            'features' => $this->getFeatureFilters($productIds),
            'price_range' => $this->getPriceRange($productIds),
        ];
    }

    /**
     * Get attribute groups and values used by the given product IDs.
     */
    private function getAttributeFilters(array $productIds): array
    {
        $rows = DB::table('ps_product_attribute AS pa')
            ->join('ps_product_attribute_combination AS pac', 'pa.id_product_attribute', '=', 'pac.id_product_attribute')
            ->join('ps_attribute AS a', 'pac.id_attribute', '=', 'a.id_attribute')
            ->join('ps_attribute_lang AS al', function ($join) {
                $join->on('a.id_attribute', '=', 'al.id_attribute')
                     ->where('al.id_lang', config('app.prestashop_lang', 1));
            })
            ->join('ps_attribute_group AS ag', 'a.id_attribute_group', '=', 'ag.id_attribute_group')
            ->join('ps_attribute_group_lang AS agl', function ($join) {
                $join->on('ag.id_attribute_group', '=', 'agl.id_attribute_group')
                     ->where('agl.id_lang', config('app.prestashop_lang', 1));
            })
            ->whereIn('pa.id_product', $productIds)
            ->select(
                'ag.id_attribute_group',
                'agl.name as group_name',
                'ag.group_type',
                'ag.is_color_group',
                'a.id_attribute',
                'al.name as attribute_name',
                'a.color',
                DB::raw('COUNT(DISTINCT pa.id_product) as product_count')
            )
            ->groupBy('ag.id_attribute_group', 'agl.name', 'ag.group_type', 'ag.is_color_group', 'a.id_attribute', 'al.name', 'a.color')
            ->orderBy('ag.position')
            ->orderBy('a.position')
            ->get();

        // Group by attribute group
        $groups = [];
        foreach ($rows as $row) {
            $gid = $row->id_attribute_group;
            if (!isset($groups[$gid])) {
                $groups[$gid] = [
                    'id' => $gid,
                    'name' => $row->group_name,
                    'type' => $row->group_type,
                    'is_color' => (bool) $row->is_color_group,
                    'values' => [],
                ];
            }
            $groups[$gid]['values'][] = [
                'id' => $row->id_attribute,
                'name' => $row->attribute_name,
                'color' => $row->color ?: null,
                'count' => (int) $row->product_count,
            ];
        }

        return array_values($groups);
    }

    /**
     * Get features and values used by the given product IDs.
     */
    private function getFeatureFilters(array $productIds): array
    {
        $rows = DB::table('ps_feature_product AS fp')
            ->join('ps_feature AS f', 'fp.id_feature', '=', 'f.id_feature')
            ->join('ps_feature_lang AS fl', function ($join) {
                $join->on('f.id_feature', '=', 'fl.id_feature')
                     ->where('fl.id_lang', config('app.prestashop_lang', 1));
            })
            ->join('ps_feature_value AS fv', 'fp.id_feature_value', '=', 'fv.id_feature_value')
            ->join('ps_feature_value_lang AS fvl', function ($join) {
                $join->on('fv.id_feature_value', '=', 'fvl.id_feature_value')
                     ->where('fvl.id_lang', config('app.prestashop_lang', 1));
            })
            ->whereIn('fp.id_product', $productIds)
            ->select(
                'f.id_feature',
                'fl.name as feature_name',
                'fv.id_feature_value',
                'fvl.value as feature_value',
                DB::raw('COUNT(DISTINCT fp.id_product) as product_count')
            )
            ->groupBy('f.id_feature', 'fl.name', 'fv.id_feature_value', 'fvl.value')
            ->orderBy('f.position')
            ->get();

        // Group by feature
        $features = [];
        foreach ($rows as $row) {
            $fid = $row->id_feature;
            if (!isset($features[$fid])) {
                $features[$fid] = [
                    'id' => $fid,
                    'name' => $row->feature_name,
                    'values' => [],
                ];
            }
            $features[$fid]['values'][] = [
                'id' => $row->id_feature_value,
                'name' => $row->feature_value,
                'count' => (int) $row->product_count,
            ];
        }

        return array_values($features);
    }

    /**
     * Get the price range (min/max) for the given product IDs.
     */
    private function getPriceRange(array $productIds): array
    {
        $result = DB::table('ps_product')
            ->whereIn('id_product', $productIds)
            ->where('active', 1)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return [
            'min' => round((float) ($result->min_price ?? 0), 2),
            'max' => round((float) ($result->max_price ?? 0), 2),
        ];
    }
}
