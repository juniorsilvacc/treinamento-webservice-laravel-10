<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct(
        Product $product
    ) {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $products = $this->product->getResults($request->all(), 5);

        return response()->json($products, 200);
    }

    public function create(CreateProductRequest $request)
    {
        $product = $this->product->create($request->validated());

        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->product->find($id);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $product->update($request->validated());

        return response()->json($product, 200);
    }
}
