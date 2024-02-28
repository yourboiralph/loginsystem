<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Resources\AuthenticatedUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function authenticate(AuthenticateUserRequest $payload){
        
        $email = $payload->email;
        $password = $payload->password;

        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.'],
            ]);
        }

        $response = (object) [
            'user' => new UserResource($user),
            'token' => $user->createToken('auth')->plainTextToken
        ];

        return new AuthenticatedUserResource($response);
    }

    public function getUser(Request $request){
        $user = $request->user();
        return new UserResource($user);
    }

    public function logoutUser(Request $request){
        $user = $request->user();
        $user->tokens()->delete();
        return 'Logout!';
    }
}
