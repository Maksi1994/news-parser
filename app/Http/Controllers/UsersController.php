<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    public function regist(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'password' => 'required|min:6',
            'repeat_password' => 'required|same:password',
            'email' => 'required|unique:users',
        ]);
        $success = false;

        if (!$validation->fails()) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'token' => Str::random(30)
            ]);

            $success = true;
        }

        return $this->success($success);
    }

    public function acceptRegistration(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'token' => 'required|exists:users'
        ]);

        if (!$validation->fails()) {
            $user = User::where('token', $request->token)->first();
            $user->token = null;
            $user->save();
        }

        return response()->redirectTo('/');
    }

    public function getOne(Request $request)
    {
        $user = User::find($request->id);

        return new UserResource($user);
    }

    public function delete(Request $request)
    {
        $success = (boolean)User::destroy($request->id);

        return $this->success($success);
    }
}
