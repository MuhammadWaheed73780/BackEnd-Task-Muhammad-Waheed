<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->fails()) {
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

        dd($req->all());
        if ($user && Hash::check($req->password, $user->password)) {
            // Authentication passed, return success response
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
