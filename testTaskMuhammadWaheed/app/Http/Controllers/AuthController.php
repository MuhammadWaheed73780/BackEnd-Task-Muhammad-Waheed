<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return redirect()->route('login', ['locale' => app()->getLocale()]);
            return response()->json([
                "failed" => [
                    "status" => 400,
                    "response" => [
                        "msg" => "Validation Error",
                        "errors" => $validator->errors()
                    ]
                ]
            ], 400);
        }

        $user = User::where("email", $req->email)->first();

        
        if ($user && Hash::check($req->password, $user->password)) {
            // Authentication passed, return success response

            return redirect()->route('home', ['locale' => app()->getLocale()]);
            return response()->json([
                "success" => [
                    "status" => 200,
                    "response" => [
                        "msg" => "Login successful",
                        "user" => $user
                    ]
                ]
            ], 200);
        }
        // return redirect()->route('manageProduct');
        return redirect()->route('login', ['locale' => app()->getLocale()]);
        // Authentication failed
        return response()->json([
            "failed" => [
                "status" => 400,
                "response" => [
                    "msg" => "Invalid Credentials",
                    "errors" => "Email was not found or wrong password."
                ]
            ]
        ], 400);
    }
}
