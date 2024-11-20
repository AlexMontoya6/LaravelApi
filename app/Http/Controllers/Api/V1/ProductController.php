<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
/**
 * @group Products
 *
 *  Managing products
 */
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(9);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new CategoryResource($product);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());

        return new CategoryResource($product);
    }

    public function list()
    {
        return CategoryResource::collection(Product::all());
    }
}
