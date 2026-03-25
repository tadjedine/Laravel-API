<?php

namespace App\Repositories;

use App\Models;

class ProductRepository{

    public function getAll(array $filters = [])
    {
        // First check cache, then PrestaShop
        // return cache()->remember(
        //     'products_' . md5(json_encode($filters)),
        //     now()->addHours(2),
        //     fn() => $this->prestaShopService->getProducts($filters)
        // );
    }
    
    public function getById(int $id)
    {
        
    }
}