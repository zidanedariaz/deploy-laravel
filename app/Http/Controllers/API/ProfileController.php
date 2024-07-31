<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required',
            'bio' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $profile = Profile::updateOrCreate([
            'user_id' => auth()->user()->id
        ], [
            'age' => $request->age,
            'bio' => $request->bio,
        ]);

        return response()->json(['message' => 'profile created or updated successfully', 'data' => $profile]);
    }
}
