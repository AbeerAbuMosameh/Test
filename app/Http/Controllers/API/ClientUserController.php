<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponseTrait;
use App\Models\ClientUser;
use App\Models\VendorUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ClientUserController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $validated_data = Validator::make($request->all(), [
            'email' => 'required|email|exists:client_users,email',
            'password' => 'required|string',
        ]);

        if ($validated_data->fails()) {
            return $this->errorResponse($validated_data->errors(), trans('messages.login_failed'), 422);
        }

        $admin = ClientUser::where('email', $request->input('email'))->first();

        if (Hash::check($request->input('password'), $admin->password)) {
            $token = $admin->createToken('Client-Api-Token')->accessToken;
            $admin->setAttribute('token', $token);
            return $this->successResponse(['token' => $token], trans('auth.login_success'));
        } else {
            return $this->errorResponse([], trans('auth.failed'), 422);
        }
    }



    public function getProfile(Request $request)
    {
        $user = $request->user('client');
        return $this->successResponse(['user' => $user], trans('messages.profile_retrieved'));
    }

    public function forgetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $client_users = DB::table('client_users')->where('email', $request->email)->first();

        if (!$client_users) {
            return $this->errorResponse([], trans('passwords.user'), 404);
        }

        $status = Password::sendResetLink(['email' => $request->email]);

        //      Mail::to($request->email)->send(new ResetPasswordMail(Str::random(10)));


        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse([], trans('password.sent'));
        } else {
            return $this->errorResponse([], trans('password.reset_link_failed'), 500);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:client_users,email',
            'password' => 'required|string|min:7|confirmed',
            'phone' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = ClientUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('Client Access Token')->accessToken;

        return $this->successResponse(['token' => $token], trans('auth.register_success'), 201);
    }

    public function logout(Request $request)
    {
        $request->user('client')->tokens()->delete();
        return $this->successResponse([], trans('auth.logout_success'));
    }
}
