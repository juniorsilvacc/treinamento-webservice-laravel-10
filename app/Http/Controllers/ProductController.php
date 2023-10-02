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
        // $products = $this->product->getResults($request->all(), 5);

        $perPage = 5; // Número de itens por página
        $query = $this->product->with('category'); // Carrega o relacionamento 'category'

        // Aplicar filtros no modelo principal (Product)
        if ($request->has('name')) {
            $query->where('name', $request->input('name'));
        }

        if ($request->has('description')) {
            $query->where('description', 'LIKE', '%'.$request->input('description').'%');
        }

        $products = $query
            ->whereHas('category', function ($query) use ($request) {
                // Aplicar filtros ao relacionamento 'category'
                if ($request->has('category_id')) {
                    $query->where('id', $request->input('category_id'));
                }
            })
            ->paginate($perPage);

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

    public function delete($id)
    {
        $product = $this->product->find($id);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $product->delete();

        return response()->json($product, 204);
    }

    public function details($id)
    {
        $product = $this->product->with('category')->get()->find($id);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($product, 200);
    }
}
