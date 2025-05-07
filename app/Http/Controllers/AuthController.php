<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\User as Model;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required|min:8|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $record = Model::create($validated);

        $response = [
            'message' => "User Registered",
            'record' => $record,
        ];
        $code = 201;
        return response()->json($response, $code);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|min:8|max:255',
        ]);

        $record = Model::where('username', $validated['username'])->first();
        $isValid = $record ? Hash::check($validated['password'], $record->password) : false;

        if ($record && $isValid) {
            $record->tokens()->delete();
            $token = $record->createToken("$record->username-Token")->plainTextToken;

            $response = [
                'message' => 'User Logged In',
                'token' => $token,
                'record' => $record,
            ];
            $code = 200;
        } else {
            $response = [
                'message' => 'Invalid Credentials',
            ];
            $code = 401;
        }
        return response()->json($response, $code);
    }

    public function logout(Request $request)
    {
        $record = PersonalAccessToken::findToken($request->bearerToken())->tokenable;

        if ($record) {
            $record->tokens()->delete();
            $response = ['message' => 'User Logged Out'];
            $code = 200;
        } else {
            $response = ['message' => 'Invalid Credentials'];
            $code = 401;
        }
        return response()->json($response, $code);
    }
}
