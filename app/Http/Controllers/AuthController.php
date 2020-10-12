<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Pesan;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->pesan = new Pesan();
    }


    public function login(Request $request)
    {

        $validator = $this->pesan->valid($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->pesan->errorValid($validator);
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'pesan' => 'email atau password salah'
            ], 200);
        }
        if (auth()->user()->active == 0) {
            return response()->json([
                'status' => false,
                'pesan' => 'akun belum diaktivasi'
            ], 200);
        }

        return $this->respondWithToken(auth()->user(), $token);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['pesan' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken($user, $token)
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
