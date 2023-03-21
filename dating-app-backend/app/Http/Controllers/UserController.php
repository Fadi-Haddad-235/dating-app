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
    public function getUserProfile(Request $request)
    {
        $user_id = Auth::id();
        $user_profile = DB::table('app_users')->where('id',$user_id)->get();
        return response()->json([
            'status' => 'success',
            'users' => $user_profile
        ]);
        return response()->json([
            'status' => 'error',
            'message' => "error retrieving user's data"
        ]);
    }
    public function editProfile(Request $request)
    {
        $user = Auth::user();  //retrieves the entire user model instance
        $request->validate([
            'age' => 'required|integer',
            'name' => 'required',
            'bio' => 'required',
            'location' => 'required|string',
            'email' => 'required|string|email',
        ]);
        $user->fill($request->only([
            'name',
            'email',
            'birthdate',
            'location',
            'bio',
        ]));
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
                'liked_user_id' => $liked_user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('notifications')->insert([
                'user_id' => Auth::id(),
                'sender_id' => $liked_user_id,
                'type' => 'like',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'you have successfully liked this user'
            ]);
        }

        public function blockUser(Request $request, $id)
        {
            $user_id = Auth::id();
            $blocked_user_id = $id;
            $blockCount = DB::table('blocks')
            ->where('user_id', Auth::id())
            ->where('blocked_user_id', $blocked_user_id)
            ->count();

            if ($blockCount > 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'you have already blocked this user'
                ]);
            }

            DB::table('blocks')->insert([
                'user_id' => Auth::id(),
                'blocked_user_id' => $blocked_user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                
            ]);

            DB::table('notifications')->insert([
                'user_id' => Auth::id(),
                'sender_id' => $blocked_user_id,
                'type' => 'block',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'you have successfully blocked this user'
            ]);
        }
        public function unblockUser(Request $request, $id)
        {
            $user_id = Auth::id();
            $blocked_user_id = $id;
            $blockCount = DB::table('blocks')
            ->where('user_id', Auth::id())
            ->where('blocked_user_id', $blocked_user_id)
            ->count();

            if ($blockCount == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'user is not blocked'
                ]);
            }

            DB::table('blocks')
            ->where('user_id', $user_id)
            ->where('blocked_user_id', $blocked_user_id)
            ->delete();

            DB::table('notifications')->insert([
                'user_id' => Auth::id(),
                'sender_id' => $blocked_user_id,
                'type' => 'unblock',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'user unblocked successfully'
        ]);
        }

        public function unlikeUser(Request $request, $id)
        {
            $user_id = Auth::id();
            $liked_user_id = $id;
            $likeCount = DB::table('likes')
                ->where('user_id', $user_id)
                ->where('liked_user_id', $liked_user_id)
                ->count();
        
            if ($likeCount == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'user is not liked'
                ]);
            }
        
            DB::table('likes')
                ->where('user_id', $user_id)
                ->where('liked_user_id', $liked_user_id)
                ->delete();

            DB::table('notifications')->insert([
                'user_id' => Auth::id(),
                'sender_id' => $liked_user_id,
                'type' => 'unlike',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        
            return response()->json([
                'status' => 'success',
                'message' => 'user unliked successfully'
            ]);
        }

        public function search(Request $request) 
        {
         $param = $request->input('q');

         $users = DB::table('app_users')
         ->where('name', 'LIKE', '%'.$param.'%')
         ->get();

         return response()->json([
            'status'=>'success',
            'data' => $users,
         ]);
        }

        public function viewNotifications(Request $request)
        {
        $notifications = DB::table('notifications')
        ->where('user_id', Auth::id())
        ->get();
        return response()->json([
            'status' => 'success',
            'users' => $notifications
        ]);
        }


}