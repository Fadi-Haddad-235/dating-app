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
        $gender=Auth::user()->gender;

        if ($gender=='male'){
            $oppsiteGender='female';
        }
        else {
            $oppsiteGender = 'male';
        }

        $users = DB::table('app_users')->where('gender', $oppsiteGender)->get();
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

    public function viewSomeProfile($id)
    {
        $user = DB::table('app_users')->where('id', $id)->first();
        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>"something went wrong, user profile not found",404
            ]);
        }
        return response()->json([
            'status' => 'success',
            'user'=> $user,
        ]);
    }
    public function filterUsers(Request $request)
    {
        $maxAge = $request->input('max_age');
        $location = $request->input('location');
        $gender=Auth::user()->gender;

        if ($gender=='male'){
            $oppsiteGender='female';
        }
        else {
            $oppsiteGender = 'male';
        }

        $query = DB::table('app_users');

        if ($location) {
            $query->where('location', $location);
        }
        if ($maxAge) {
            $query->where('age', '<', $maxAge);
        }
        $query->where('gender','=', $oppsiteGender);
        $results = $query->get();

        // Return the filtered results
        return response()->json([
            'status' => 'success',
            'users' => $results
        ]);
        }
        public function likeUser(Request $request, $id)
        {
            $user_id = Auth::id();
            $liked_user_id = $id;
            $likeCount = DB::table('likes')
            ->where('user_id', Auth::id())
            ->where('liked_user_id', $liked_user_id)
            ->count();

            if ($likeCount > 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'you have already liked this user'
                ]);
            }

            DB::table('likes')->insert([
                'user_id' => Auth::id(),
                'liked_user_id' => $liked_user_id
                
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'you have successfully liked this user'
            ]);
        }
}