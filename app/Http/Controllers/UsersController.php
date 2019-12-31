<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Notifications\RegistrationUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    public function regist(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'password' => 'required|min:6',
            'email' => 'required|unique:users',
        ]);
        $success = false;

        if (!$validation->fails()) {
            User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'registration_token' => Str::random(30),
                'active' => 0
            ])->notify(new RegistrationUser);

            $success = true;
        }

        return $this->success($success);
    }

    public function acceptRegistration(Request $request)
    {
        $validation = Validator::make(['registration_token' => $request->token], [
            'registration_token' => 'required|exists:users'
        ]);

        if (!$validation->fails()) {
            User::where('registration_token', $request->token)->update([
                'registration_token' => null,
                'active' => 1
            ]);
        }

        return response()->redirectTo('/');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = $request->user();
            $authToken = $user->createToken('Auth Token');

            if ($request->remember_me) {
                $token = $authToken->token;
                $token->expires_at = Carbon::now()->addYear();
                $token->save();
            }

            return [
                'success' => true,
                'access_token' => $authToken->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($authToken->token->expires_at)->toDateTime()
            ];
        }

        return ['success' => false];
    }

    public function getOne(Request $request)
    {
        $user = User::find($request->id);

        return new UserResource($user);
    }

    public function getCurrUser(Request $request)
    {
        return new UserResource($request->user());
    }

    public function isUniqueEmail(Request $request)
    {
        $existingUser = User::where('email', $request->email)->exists();

        return $this->success(!$existingUser);
    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
    }
}
