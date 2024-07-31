<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'owner'])->except(['index', 'show']);
    }

    public function index()
    {
        $books = Book::all();
        return response()->json([
            "message" => "Books retrieved successfully",
            "data" => $books->load('category')
        ]);
    }

    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json([
            "message" => "Book retrieved successfully",
            "data" => $book->load(['category', 'list_borrows'])
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'stok' => 'required',
            'image' => 'nullable|mimes:jpg,png',
            'category_id' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $image_name = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $image_name);

            $path = env('APP_URL') . '/storage/images/';
            $data['image'] = $path . $image_name;
        }

        Book::create($data);

        return response()->json([
            "message" => "Book created successfully",
            "data" => $data
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        if ($request->hasFile('image')) {
            if ($book->image) Storage::delete('/public/images/' . basename($book->image));

            $image_name = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $image_name);

            $path = env('APP_URL') . '/storage/images/';
            $book->image = $path . $image_name;
        }

        $book->title = $request->title;
        $book->summary = $request->summary;
        $book->stok = $request->stok;
        $book->category_id = $request->category_id;

        $book->save();
        return response()->json(
            [
                "message" => "Book updated successfully"
            ],
            200
        );
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->image) Storage::delete('/public/images/' . basename($book->image));

        $book->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    }
}
