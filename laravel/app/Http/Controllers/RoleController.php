<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Role::query()->paginate());
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'sometimes|array',
        ]);
        $obj = new Role();
        $obj->fill($data);
        $obj->save();
        if (isset($data['permissions'])) {
            $obj->permissions()->sync($data['permissions']);
        }

        return response()->json($obj)->setStatusCode(201);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'sometimes|array',
        ]);
        $obj = Role::query()->findOrFail($id);
        $obj->fill($data);
        $obj->save();
        if (isset($data['permissions'])) {
            $obj->permissions()->sync($data['permissions']);
        }

        return response()->noContent();
    }

    public function destroy(string $id): Response
    {
        $keyName = app(Role::class)->getKeyName();
        Role::query()->where($keyName, $id)->delete();
        return response()->noContent();
    }
}
