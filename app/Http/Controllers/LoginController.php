<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Auth\app\Resources\UserResource;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\app\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Traits\JsonResponseTrait;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    use JsonResponseTrait, AuthenticatesUsers;

    public function login(LoginRequest $request)
    {
        //code d'auth avec sanctum
        $user = User::findForSanctum($request->input('phone'));

        //check the password
        if (
            !$user || !Hash::check($request->password, $user->password)
        ) {
            return $this->sendErrorResponse('Verifier votre authentification svp !!!');
        }

        if ($user->active) {
            if ($user->verified) {
                $token = $user->createToken($request->input('role'))->plainTextToken;

                $reponse = [
                    'user' => new UserResource($user),
                    'token' => $token
                ];

                return $this->sendResponse($reponse, ' Vous etes bien connecté sur notre app');
            } else {
                return $this->sendErrorResponse('Votre compte n\'est pas vérifier');
            }
        } else {
            return $this->sendErrorResponse('Votre compte est désativer');
        }
    }
}
