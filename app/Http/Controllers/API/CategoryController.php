<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','owner'])->except(['index', 'show']);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            "message" => "Categories retrieved successfully",
            "data" => $categories
        ], 200);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json([
            "message" => "Category retrieved successfully",
            "data" => $category->load('list_books')
        ]);
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json([
            "message" => "Category created successfully",
            "data" => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->update($request->all());

        return response()->json([
            "message" => "Category updated successfully",
            "data" => $category
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
