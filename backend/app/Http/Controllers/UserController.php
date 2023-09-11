<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        $user = User::create($request->all());

        $path = storage_path('app/public/images/');
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = 'user-' . $user->id. '.'. $file->getClientOriginalExtension();
            info($file);
            info($fileName);
            $file->storeAs('public/images', $fileName);
            $user->update(['profile' => $fileName]);
        }

        return response()->json(['message' => 'User created successfully','user' =>  new UserResource($user)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->hasFile('profile')) {
            Storage::delete('public/images/' . $user->profile);
            $file = $request->file('profile');
            $fileName = 'user-' . $user->id. '.'. $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            $user->update(['image' => $fileName]);
        }

        $user->update(Arr::except($request->except('profile'), ['profile']));

        return response()->json(['message' => 'User updated successfully','user' =>  new UserResource($user)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->profile != null) {
            Storage::delete('public/images/' . $user->profile);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
