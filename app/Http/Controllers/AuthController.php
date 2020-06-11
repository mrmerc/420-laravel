<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Socialite;
use Widmogrod\Monad\Either\{ Either, Left, Right };
use Log;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'redirectToProvider',
                'handleProviderCallback'
            ]
        ]);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return JsonResponse
     */
    public function redirectToProvider()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * Obtain the user information from Google.
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handleProviderCallback(): JsonResponse
    {
        $user = Socialite::driver('google')->stateless()->user();

        $userData = [
            'provider_id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
        ];

        Validator::validate($userData, [
            'provider_id' => 'numeric',
            'name' => 'string|nullable',
            'email' => 'string|email',
            'avatar' => 'string|nullable',
        ]);

        $result = $this->login($userData);

        if ($result instanceof Left) {
            Log::error($result->extract());
            return response()->json([
                'error' => 'Database error'
            ], 500);
        }

        //$this->respondWithToken($result->extract());
        return response()->json([
            'access_token' => $result->extract()
        ], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param array $userData
     * @return Either
     */
    public function login(array $userData): Either
    {
        try {
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                $user = new User;

                $usernameFromEmail = explode('@', $userData['email'])[0];
                $foundNicknamesCounter = User::where(
                    'username',
                    'like',
                    '%' . $usernameFromEmail . '%'
                )->count();

                $nicknameWithId = $usernameFromEmail . '#' . $foundNicknamesCounter;

                $user->username = $nicknameWithId;
                $user->email = $userData['email'];
                $user->password = '83JvTqXaLLQRPkTf';
                $user->name = $userData['name'];
                $user->avatar = $userData['avatar'];
                $user->provider = 'google';
                $user->provider_id = $userData['provider_id'];
                $user->save();

                $role = Role::where('title', 'user')->first();
                $user->roles()->save($role);
            }

            $token = auth()->login($user);
            return Right::of($token);
        } catch (\Throwable $e) {
            return Left::of($e);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token
        ]);
    }
}
