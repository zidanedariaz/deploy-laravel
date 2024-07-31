<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('owner')->only('index');
    }
    
    public function index()
    {
        $borrows = Borrow::all();
        return response()->json([
            "message" => "Borrow records retrieved successfully",
            "data" => $borrows->load(['book', 'user'])
        ]);
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'borrow_date' => 'required',
            'load_date' => 'required',
        ]);

        $borrow = Borrow::updateOrCreate([
            'book_id' => $request->book_id,
            'user_id' => auth()->user()->id
        ], [
            'borrow_date' => $request->borrow_date,
            'load_date' => $request->load_date,
            'status' => 'borrowed'
        ]);
        return response()->json([
            "message" => "Borrow record created or updated successfully",
            "data" => $borrow
        ], 201);
    }

  
}
