<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function getAllProducts($per_page = -1)
    {
        if($per_page == -1){
            return Product::orderByRaw('field(name, "Other")')->orderBy('created_at')->get();    
        }
        return Product::orderByRaw('field(name, "Other")')->orderBy('created_at')->paginate($per_page);
    }

    public function getProductById($id)
    {
        return Product::find($id);
    }

    public function create($data)
    {
        return Product::create($data);
    }

    public function update($products, $data)
    {
        return $products->update($data);
    }

    public function delete($products)
    {
        return $products->delete($products);
    }
}
