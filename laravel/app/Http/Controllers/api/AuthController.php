<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a user token for an existing user
     *
     * @param Request $request Form data with email and password
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = $request->all();

        if (!Auth::attempt($credentials)) {
            return response(['error' => Config::get('constants.http_error.e401')], 401);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        return response(['user' => Auth::user(), 'access_token' => $accessToken]);
    }

    /**
     * Create a user and user token
     *
     * @param Request $request Form data with name, email and password
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $data = $request->all();

        if (User::where('email', $data['email'])->count() != 0) {
            return response()->json(['error' => Config::get('constants.http_error.e400')], 400);
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $success['token'] = $user->createToken('authToken')->accessToken;

        return response()->json(['success' => $success], 200);
    }
}
