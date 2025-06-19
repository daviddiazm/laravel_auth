<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "role" => "required",
            "email" => "required",
            "password" => "required",
        ]);

        if (!$validator) {
            $data = [
                'message' => "no se pude guardar el usuario",
                'erros' => $validator->errors()->all(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }

        $user = User::create([
            "name" => $request->get("name"),
            "role" => $request->get("role"),
            "email" => $request->get("email"),
            "password" => $request->get("password")
        ]);
        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "password" => "required",
        ]);

        if (!$validator) {
            $data = [
                'message' => "no se pude ingresar el usuario",
                'erros' => $validator->errors()->all(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }

        $credentials = $request->only(['email', 'password']);

        try {
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json(["error" => "invalid credentials"], 401);
            }
            return response()->json(["token" => $token], 200);
        } catch (JWTException $e) {
            $data = [
                "message" => "no se pudo generar el token",
                "error" => $e->getMessage()
            ];
            return response()->json($data, 400);
        }
    }

    public function getUser(){
        $user = Auth::user();
        return response()->json($user,200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
