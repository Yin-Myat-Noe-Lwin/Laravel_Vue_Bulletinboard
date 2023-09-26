<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Maatwebsite\Excel\Facades\Excel;
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
        return response()->json(['users' => UserResource::collection($users)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        if($request->flg == 'confirm'){

            $user = User::create($request->all());

            $path = storage_path('app/public/images/');
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $randomFileName = Str::random(40);
                $fileName = $randomFileName. '.'. $file->getClientOriginalExtension();
                $file->storeAs('public/images', $fileName);
                $user->update(['profile' => $fileName]);
            }
            return response()->json(['message' => 'User created successfully','user' =>  new UserResource($user)]);
        }

        return response()->json(['message' => 'User Confirmed successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(['user' => new UserResource($user) ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->hasFile('profile')) {
                Storage::delete('public/images/' . $user->profile);
                $file = $request->file('profile');
                $randomFileName = Str::random(40);
                $fileName = $randomFileName. '.'. $file->getClientOriginalExtension();
                $file->storeAs('public/images', $fileName);
                $user->update(['profile' => $fileName]);
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

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function getUserImage(User $user)
    {
        $path = 'public/images/' . $user->profile;
        $storagePath = storage_path('app/' . $path);

        if (file_exists($storagePath)) {
            return response()->file($storagePath);
        } else {
            return response()->json(['error' => 'Image not found'], 404);
        }
    }

}
