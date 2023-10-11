<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        //search params for user data
        $searchNameQuery = $request->input('searchName');

        $searchEmailQuery = $request->input('searchEmail');

        $searchFromDateQuery = $request->input('searchFromDate');

        $searchToDateQuery = $request->input('searchToDate');

        $usersQuery = User::query();

        //if admin, show all users expect deleted, if user, show users created by him or herself
        if (Auth::user()->isAdmin()) {

           //show all Users

        } else {

            //show users created by loggedin user
            $user = Auth::user();

            $usersQuery->where('create_user_id', $user->id);

        }

        //search with name,email, created date
        $usersQuery->when($searchNameQuery, function ($query) use ($searchNameQuery) {

            return $query->where('name', 'like', "%$searchNameQuery%");

        });

        $usersQuery->when($searchEmailQuery, function ($query) use ($searchEmailQuery) {

            return $query->where('email', 'like', "%$searchEmailQuery%");

        });

        $usersQuery->when($searchFromDateQuery, function ($query) use ($searchFromDateQuery) {

            $formattedFromDate = date('Y-m-d 00:00:00', strtotime($searchFromDateQuery));

            return $query->whereDate('created_at', '>=', $formattedFromDate($searchFromDateQuery) );

        });

        $usersQuery->when($searchToDateQuery, function ($query) use ($searchToDateQuery) {

            $formattedToDate = date('Y-m-d 00:00:00', strtotime($searchToDateQuery));

            return $query->whereDate('created_at', '<=', $formattedToDate($searchToDateQuery));

        });

        $users = $usersQuery->paginate(5);

        $allUsers = User::all();

        //allUsers for user data, users for pagination
        return response()->json(['users' => $users, 'allUsers' => $allUsers]);
    }

    public function showAllUsers(Request $request)
    {
        //search params for user data
        $searchNameQuery = $request->input('searchName');
        $searchEmailQuery = $request->input('searchEmail');
        $searchFromDateQuery = $request->input('searchFromDate');
        $searchToDateQuery = $request->input('searchToDate');

        $usersQuery = User::query();

        //search with name,email, created date
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

        $users = $usersQuery->paginate(5);

        $allUsers = User::all();

        //allUsers for user data, users for pagination
        return response()->json(['users' => $users, 'allUsers' => $allUsers]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        if($request->flg == 'confirm') {

            $user = User::create($request->all());

            $path = storage_path('app/public/images/');

            //check if there is folder name "images", if not, create "images" folder
            if (!is_dir($path)) {

                mkdir($path, 0777, true);

            }

            //get user input profile image , change custom file name and store under "images" folder
            if ($request->hasFile('profile')) {

                $file = $request->file('profile');

                $randomFileName = Str::random(20);

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
            $randomFileName = Str::random(20);
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

        $loggedinUser = Auth::user();

        $user->update(['deleted_user_id' => $loggedinUser->id]);

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
