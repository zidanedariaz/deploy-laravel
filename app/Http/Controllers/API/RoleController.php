<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::all();
        return response()->json([
            "message" => "Roles retrieved successfully",
            "data" => $roles
        ], 200);
    }

    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());

        return response()->json([
            "message" => "Role created successfully",
            "data" => $role
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Role not found'], 404);
        
        $role->update($request->all());
        
        return response()->json(
            [
                "message" => "Role updated successfully",
                "data" => $role
            ],
            200
        );
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) return response()->json(['message' => 'Role not found'], 404);
        
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
