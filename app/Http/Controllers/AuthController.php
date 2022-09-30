<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $users;
    public function __construct(User $User)
    {
        //apply middleware to all route /api except /api/login
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->users = $User;
    }

    public function register(Request $request){
        try {
            //code...
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message'    => 'Validate failed',
                'status' => 'Failed',
                'errors' => $e->errors(),
            ], 404);
        }

        $userList = $this->users->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($userList) {
            $response = response()->json([
                'message' => 'Register user successful',
                'status' => 'Success',
            ], 201);
        } else $response = response()->json([
            'message' => 'Register user failed',
            'status' => 'Failed',
        ], 404);
        return $response;
    }

    public function login(Request $request){
        try {
            //code...
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message'    => 'Validate failed',
                'status' => 'Failed',
                'errors' => $e->errors(),
            ], 404);
        }

        // $credentials = [
        //     'email' => $request->email, 
        //     'password' => Hash::make($request->password)
        // ];

        $user = $this->users->getUser($request->email);
        // var_dump($user);
        // die();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return auth()->login($user);   
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
