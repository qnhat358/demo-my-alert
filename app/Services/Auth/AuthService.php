<?php

namespace App\Services\Auth;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Token;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class AuthService implements AuthServiceInterface
{
    private $users;

    public function __construct(User $User)
    {
        $this->users = $User;
    }

    public function register($request)
    {
        $userList = $this->users->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($userList) {
            $response = response()->json([
                'message' => 'Register user successful',
                'status' => 'success',
            ], 201);
        } else $response = response()->json([
            'message' => 'Register user failed',
            'status' => 'error',
        ], 404);
        return $response;
    }

    public function login($request)
    {
        $user = $this->users->getUser($request->email);
        if (is_null($user)){
            // dd($user);
            // var_dump($user);
            // die();
            return response()->json([
                'message' => 'Account not found',
                'status' => 'error',
            ], 404);
        }
        else {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 'error'
            ], 401);
            }
            $token = auth()->login($user);
            $token = auth()->customClaims(['exp' => Carbon::now()->addSeconds(45)->timestamp])->fromUser($user);
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);
        }
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'success',
                'message' => 'Log out failed',
                'errors' => $e->getMessage(),
            ], 200);
        }
    }
}
