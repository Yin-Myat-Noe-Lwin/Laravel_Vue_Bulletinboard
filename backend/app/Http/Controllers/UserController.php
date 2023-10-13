<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\SignupRequest;

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

         //get logged in user
         $user = Auth::user();

        if ($user->isAdmin()) {

           //if logged in user is Admin, show all users except deleted users
           $usersQuery = User::query();

        } else {

            //if User, show users created by loggedin user
            $usersQuery = User::where('create_user_id', $user->id);

        }

        if($searchNameQuery || $searchEmailQuery || $searchFromDateQuery || $searchToDateQuery ) {

            //search user data with name,email, created_at date
            $usersQuery->when($searchNameQuery, function ($query) use ($searchNameQuery) {

                return $query->where('name', 'like', "%$searchNameQuery%");

            });

            $usersQuery->when($searchEmailQuery, function ($query) use ($searchEmailQuery) {

                return $query->where('email', 'like', "%$searchEmailQuery%");

            });

            $usersQuery->when($searchFromDateQuery, function ($query) use ($searchFromDateQuery) {

                return $query->whereDate('created_at', '>=',date('Y-m-d 00:00:00', strtotime($searchFromDateQuery)) );

            });

            $usersQuery->when($searchToDateQuery, function ($query) use ($searchToDateQuery) {

                return $query->whereDate('created_at', '<=', date('Y-m-d 00:00:00', strtotime($searchToDateQuery)));

            });

            $usersQuery->when($searchFromDateQuery && $searchToDateQuery, function ($query) use ($searchFromDateQuery, $searchToDateQuery) {
                $formattedFromDate = date('Y-m-d 00:00:00', strtotime($searchFromDateQuery));
                $formattedToDate = date('Y-m-d 23:59:59', strtotime($searchToDateQuery));
                return $query->whereBetween('created_at', [$formattedFromDate, $formattedToDate]);
            });

        }

        //paginate users with 5 users per one page
        $users = $usersQuery->paginate(5);

        $allUsers = User::all();

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

        //search with name,email, created_at date
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

        $usersQuery->when($searchFromDateQuery && $searchToDateQuery, function ($query) use ($searchFromDateQuery, $searchToDateQuery) {
            $formattedFromDate = date('Y-m-d 00:00:00', strtotime($searchFromDateQuery));
            $formattedToDate = date('Y-m-d 23:59:59', strtotime($searchToDateQuery));
            return $query->whereBetween('created_at', [$formattedFromDate, $formattedToDate]);
        });

        //paginate users with 5 users per one page
        $users = $usersQuery->paginate(5);

        $allUsers = User::all();

        return response()->json(['users' => $users, 'allUsers' => $allUsers]);
    }

    public function signup(SignupRequest $request)
    {
        //register when not logged in
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

        //after create user then update create_user_id and updated_user_id with newly created user id
        $user->update([
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id,
        ]);

        //create token
        $token = $user->createToken('auth_token')->plainTextToken;

        //token expiry date
        $cookie = cookie('token', $token, 60 * 24 * 30 );

        return response()->json([
            'message' => 'User sign up successful',
            'user' =>  new UserResource($user),
            'token' => $token
        ])->withCookie($cookie);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        if($request->flg == 'confirm') {
            //if flg was confirm, create new user
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        //change user profile image
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
        //delete user profile image
        if ($user->profile != null) {

            Storage::delete('public/images/' . $user->profile);

        }

        //current logged in user
        $loggedinUser = Auth::user();

        //add logged in user id into deleted_user_id
        $user->update(['deleted_user_id' => $loggedinUser->id]);

        //delete user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        //update user password
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['message' => 'User password updated successfully']);
    }

}
