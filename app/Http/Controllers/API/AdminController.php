<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponseTrait;
use App\Mail\ResetPasswordMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    use ApiResponseTrait;

    public function login(Request $request)
    {
        // Validate the request data
        $validated_data = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|string',
        ]);

        if ($validated_data->fails()) {
            return $this->errorResponse([], $validated_data->errors()->first(), 422);
        }
        try {
            $response = Http::post('http://127.0.0.1:8081/oauth/token', [
                'grant_type' => 'password',
                'client_id' => 1,
                'client_secret' => 'xf7vFLANs0ByrbZN7eOddMgK6yw12GZeuqaUM0MW',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '',
            ]);

            $responseData = $response->json();

            if (isset($responseData['access_token'])) {
                $accessToken = $responseData['access_token'];
                return $this->successResponse(['token' => $accessToken], trans('auth.login_success'));
            } else {
                return $this->errorResponse([], 'Error: Access token not found in response.', 500);
            }
        } catch (\Exception $e) {
            return $this->errorResponse([], 'Error: Unable to connect to the OAuth server.', 500);
        }
    }

//    public function login(Request $request)
//    {
//
//        $validated_data = Validator::make($request->all(), [
//            'email' => 'required|email|exists:admins,email',
//            'password' => 'required|string',
//        ]);
//
//        if ($validated_data->fails()) {
//            return $this->errorResponse([], $validated_data->errors()->first(), 422);
//        }
//
//
//
//        $admin = Admin::where('email', $request->input('email'))->first();
//
//        if (Hash::check($request->input('password'), $admin->password)) {
//            $token = $admin->createToken('Admin-Api-Token')->accessToken;
//            $admin->setAttribute('token', $token);
//            return $this->successResponse(['token' => $token], trans('auth.login_success'));
//        } else {
//            return $this->errorResponse([], trans('auth.failed'), 422);
//        }
//
//    }

    public function logout(Request $request)
    {
        if (auth()->check()) {
            $token = $request->user('admin')->token();
            $revoked = $token->revoke();
            $revoked = $request->user('admin')->tokens()->delete();

            if ($revoked) {
                return $this->successResponse([], trans('auth.logout_success'));
            } else {
                return $this->errorResponse([], trans('auth.logout_failed'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return $this->errorResponse([], trans('auth.not_authenticated'), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getProfile(Request $request)
    {
        $user = $request->user('admin');
        return $this->successResponse(['user' => $user], trans('messages.profile_retrieved'));
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ]);

        $admin = DB::table('admins')->where('email', $request->email)->first();
        if (!$admin) {
            return $this->errorResponse([], trans('passwords.user'), 404);
        }

        $status = Password::sendResetLink(['email' => $request->email]);

        //      Mail::to($request->email)->send(new ResetPasswordMail(Str::random(10)));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse([], trans('passwords.sent'));
        } else {
            return $this->errorResponse([], trans('passwords.reset_link_failed'), 500);
        }
    }
}
