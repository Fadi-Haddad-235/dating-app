<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(){

        $users = app_users::all();

        return response->json(['users' => $users]);
    }
}
