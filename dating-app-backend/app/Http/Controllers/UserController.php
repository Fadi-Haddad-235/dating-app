<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = DB::table('app_users')->get();
        return response()->json([
            'status' => 'success',
            'users' => $users
        ]);
    }
    public function editProfile(Request $request)
    {
        $user = Auth::user();  //retrieves the entire user model instance


        $user->fill($request->only([
            'name',
            'email',
            'birthdate',
            'location',
            'bio',
            'gender',
            'profile_picture',
        ]));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'data'=> $user
        ]);
    }
}