<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $phone = 0;
        $email = '';
        $username = '';

        if (is_numeric($request->usermailorphone)) {
            $phone = $request->get('usermailorphone');
        }
        if (filter_var($request->get('usermailorphone'), FILTER_VALIDATE_EMAIL)) {
            $email = $request->get('usermailorphone');
        }
        else{
            $username = $request->get('usermailorphone');
        }
        if($phone===0 &&  $email==='' && $username=== ''){
            return response()->json(['message' => 'Invalid User'], 400);
        }
        $user = User::where(['Email' => $email,'Status' => 1])->orWhere(['Mobile' => $phone,'Status' => 1])->orWhere(['Name' => $username,'Status' => 1])->first();

        if ($phone && $token = JWTAuth::attempt(['Mobile' => $phone, 'password' => $request->password,'Status' => 1])) {
            return $this->respondWithToken($token);
        }
        elseif ($username && $token = JWTAuth::attempt(['Name' => $username, 'password' => $request->password,'Status' => 1]) ) {
            return $this->respondWithToken($token);
        }
        elseif ($email && $token = JWTAuth::attempt(['Email' => $email, 'password' => $request->password,'Status' => 1]) ) {
            return $this->respondWithToken($token);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User Email/Phone or Password!'
            ], 401);
        }
    }

    public function me()
    {
        return response()->json($this->guard()->user());

    }

    public function logout()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'Users' => Auth::user(),
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
