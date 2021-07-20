<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'code' => 400
            ],400);
        }

        if (!Auth::attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 401
            ], 401);
        }

        $user = User::where('email',$data['email'])->first();

        $accessToken = $user->createToken('api')->plainTextToken;
        // $token = $user->createToken('web',['admin']);
        return response()->json([
            'sccuess' => true,
            'user' => $user,
            'access_token' => $accessToken
        ],200);
    }

    public function register(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:191',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'code' => 400
            ],400);
        }

        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        $accessToken = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $accessToken
        ],201);
    }

    public function logout (Request $request) {
        $user = Auth::user();
        $token = Auth::user()->currentAccessToken();

        $user->tokens()->where('id', $token->id ?? 0)->delete();
    
        return response()->json([],204);
    }
}
