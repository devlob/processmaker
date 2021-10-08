<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Utopia\Filters\ProductFilters;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::filter(new ProductFilters(request()))
            ->select('id', 'name', 'description', 'code', 'status', 'created_at')
            ->paginate(5);

        return new ProductCollection($products);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'code' => $request->code,
            'status' => $request->status
        ]);

        return new ProductResource($product->fresh());
    }
}
