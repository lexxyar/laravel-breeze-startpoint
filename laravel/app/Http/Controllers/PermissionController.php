<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $data = Permission::query()->paginate()->withQueryString();

        return JsonResource::collection($data);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions'
        ]);
        $guard = array_keys(config('auth.defaults.guard'));
        $obj = new Permission();
        $obj->fill($data);
        $obj->guard = $guard;
        $obj->save();

        return response()->json($obj)->setStatusCode(201);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);
        $obj = Permission::query()->findOrFail($id);
        $obj->fill($data);
        $obj->save();

        return response()->noContent();
    }

    public function destroy(string $id): Response
    {
        $keyName = app(Permission::class)->getKeyName();
        Permission::query()->where($keyName, $id)->delete();
        return response()->noContent();
    }
}
