<?php

namespace App\Http\Controllers;

use App\Events\UserCreatedEvent;
use App\Events\UserDeletedEvent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $data = User::query()->paginate()->withQueryString();

        return JsonResource::collection($data);
    }

    public function show(string $id): JsonResponse
    {
        $data = User::query()->findOrFail($id);

        return response()->json($data);
    }

    public function store(Request $request, string $id): JsonResponse
    {
        $keyName = app(User::class)->getKeyName();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'roles' => ['array', 'sometimes']
        ]);

        $password = Str::random(8);
        $obj = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);
        if (isset($request->roles)) {
            $obj->roles()->sync($request->roles);
            $obj->load('roles');
        }
        event(new UserCreatedEvent($obj, $password));

        return response()->json($obj)->setStatusCode(201);
    }

    public function update(Request $request, string $id): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',' . $id],
            'roles' => ['array', 'sometimes']
        ]);

        $obj = User::query()->findOrFail($id);
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->save();
        if (isset($request->roles)) {
            $obj->roles()->sync($request->roles);
            $obj->load('roles');
        }

        return response()->noContent();
    }

    public function destroy(string $id): Response
    {
        $obj = User::query()->findOrFail($id);

        event(new UserDeletedEvent($obj));

        $obj->delete();

        return response()->noContent();
    }
}
