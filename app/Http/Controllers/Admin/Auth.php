<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class Auth extends Controller
{
    public function login(){
        return view("admin/login", [
            "common_data" => [
                "title" => "Admin - Login"
            ]
        ]);
    }

    public function ajaxLogin(){

        $result = \Illuminate\Support\Facades\Auth::attempt(["email" => Request::post("email"), "password" => Request::post("password")]);

        if(!$result)
            return ["success" => false, "message" => "Failed to login. Invalid email or password."];
        else
            return ["success" => true];
    }
}
