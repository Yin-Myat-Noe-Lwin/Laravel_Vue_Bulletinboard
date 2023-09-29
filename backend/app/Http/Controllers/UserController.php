<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $searchNameQuery = $request->input('searchName');
        $searchEmailQuery = $request->input('searchEmail');
        $searchFromDateQuery = $request->input('searchFromDate');
        $searchToDateQuery = $request->input('searchToDate');

        if ($searchNameQuery || $searchEmailQuery ||  $searchFromDateQuery ||  $searchToDateQuery)
        {
            $usersQuery = User::query();

            $usersQuery->when($searchNameQuery, function ($query) use ($searchNameQuery) {
                return $query->where('name', 'like', "%$searchNameQuery%");
            });

            $usersQuery->when($searchEmailQuery, function ($query) use ($searchEmailQuery) {
                return $query->where('email', 'like', "%$searchEmailQuery%");
            });

            $usersQuery->when($searchFromDateQuery, function ($query) use ($searchFromDateQuery) {
                return $query->whereDate('created_at', '>=', date('Y-m-d 00:00:00', strtotime($searchFromDateQuery)));
            });

            $usersQuery->when($searchToDateQuery, function ($query) use ($searchToDateQuery) {
                return $query->whereDate('created_at', '<=', date('Y-m-d 00:00:00', strtotime($searchToDateQuery)));
            });

            $users = $usersQuery->paginate(3);
        } else
        {
            $users = User::paginate(3);
        }
        return response()->json(['users' => $users]);
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

    public function changePassword(ChangePasswordRequest $request, User $user) {

        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['message' => 'User password updated successfully']);
    }

}
