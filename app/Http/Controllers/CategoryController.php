<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;

    public function __construct(
        Category $category
    ) {
        $this->category = $category;
    }

    public function index(Request $request)
    {
        $categories = $this->category->getResults($request->name);

        return response()->json($categories, 200);
    }

    public function create(Request $request)
    {
        $categoria = $this->category->create($request->all());

        return response()->json($categoria, 201);
    }
}
