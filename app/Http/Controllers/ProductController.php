<?php

namespace App\Http\Controllers;

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
}
