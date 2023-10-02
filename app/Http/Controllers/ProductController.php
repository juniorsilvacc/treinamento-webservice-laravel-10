<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $product;
    private $path = 'products';

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
        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $name = Str::kebab($request->name); // Nome do arquivo utilizando um Helper de separação de string
            $extension = $request->file('image')->extension(); // Extenção do arquivo jpg, png, gif...

            $nameFile = "{$name}.{$extension}"; // Definindo o nome do arquivo com a extensão
            $data['image'] = $nameFile; // Atribuindo na coluna 'image' o nome do arquivo com a extensão

            $upload = $request->image->storeAs($this->path, $nameFile); // Fazendo upload em products/nomearquivos.extensão

            if (!$upload) { // Se não houver upload retornar um error 500
                return response()->json(['error' => 'Fail upload'], 500);
            }
        }

        $product = $this->product->create($data);

        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->product->find($id);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($product->image) { // Se existir imagem, exclua, se não continua com o upload
                if (Storage::exists("products/{$product->image}")) {
                    Storage::delete("products/{$product->image}");
                }
            }

            $name = Str::kebab($request->name); // Nome do arquivo utilizando um Helper de separação de string
            $extension = $request->file('image')->extension(); // Extenção do arquivo jpg, png, gif...

            $nameFile = "{$name}.{$extension}"; // Definindo o nome do arquivo com a extensão
            $data['image'] = $nameFile; // Atribuindo na coluna 'image' o nome do arquivo com a extensão

            $upload = $request->image->storeAs($this->path, $nameFile); // Fazendo upload em products/nomearquivos.extensão

            if (!$upload) { // Se não houver upload retornar um error 500
                return response()->json(['error' => 'Fail upload'], 500);
            }
        }

        $product->update($data);

        return response()->json($product, 200);
    }

    public function delete($id)
    {
        $product = $this->product->find($id);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        if ($product->image) { // Excluir uma imagem vinculada ao produto
            if (Storage::exists("products/{$product->image}")) {
                Storage::delete("products/{$product->image}");
            }
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
