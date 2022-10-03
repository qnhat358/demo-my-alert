<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Token;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $users;
    public function __construct(User $User)
    {
        //apply middleware to all route /api except /api/login
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
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
                'message'    => 'validate failed',
                'status' => 'failed',
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
                'message' => 'register user successful',
                'status' => 'success',
            ], 201);
        } else $response = response()->json([
            'message' => 'register user failed',
            'status' => 'failed',
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
        $user = $this->users->getUser($request->email);
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = auth()->login($user);
        $token = auth()->customClaims(['exp' => Carbon::now()->addSeconds(45)->timestamp])->fromUser($user);
        // dd((auth()->payload())['exp']);
        // dd(Carbon::now()->timestamp);
        // $data = JWTAuth::decode(new Token($token))->toArray();
        return response()->json([
            'status' => 'Success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
                // 'expires_in' => $data['exp']
            ]
        ],200);
    }

    public function logout(){
        try {
            //code...
            auth()->logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ],200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Log out failed',
                'errors' => $e->getMessage(),
            ],200);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {   
        return response()->json([
            'status' => 'Success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // protected function createNewToken($token){
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60,
    //         'user' => auth()->user()
    //     ]);
    // }
}
