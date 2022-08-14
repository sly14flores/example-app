<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Traits\Messages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\LoginResource;

class LoginController extends Controller
{
    use Messages;

    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    /**
     * @group Authentication
     * 
     * Login user
     * 
     * Login using email and password
     * 
     * @bodyParam email string required
     * @bodyParam password string required
     * 
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ],[
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ]);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $login = $validator->valid();

        if (!Auth::attempt($login)) {
            return $this->jsonErrorInvalidCredentials();
        }

        $token = Auth::user()->createToken('authToken');
        $user = User::find(Auth::id());
        $user->token = $token->accessToken;

        $data = new LoginResource($user);

        return $this->jsonSuccessResponse($data, 200);

    }

    /**
     * @group Authentication
     * 
     * Logout
     * 
     * Logout user
     * 
     * @authenticated
     */
    public function logout(Request $request)
    {
        $revoked = Auth::guard('api')->user()->token()->revoke();
        if ($revoked) {
            return $this->jsonSuccessLogout();
        }
        return $this->jsonFailedResponse(null, 500, 'Something went wrong.');
    }

}