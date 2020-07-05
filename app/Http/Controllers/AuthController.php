<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\SocialAccount;
use App\Http\Requests\Auth\ProviderCallbackRequest;
use App\Http\Requests\Auth\ProviderUrlRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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
     * @api {get} /auth/:provider/url               Get provider's authentication URL.
     * @apiName GetProviderURL
     * @apiGroup Authentication
     *
     * @apiParam {String} provider                  Provider name
     *
     * @apiSuccess {String} url                     Authentication URL.
     *
     * @apiError (Error 500) SocialiteProviderError Failed to get authentication URL.
     *
     * @return JsonResponse
     */
    public function redirectToProvider(ProviderUrlRequest $request)
    {
        $provider = $request->validated()['provider'];
        try
        {
            return response()->json([
                'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
            ]);
        }
        catch (\Throwable $e)
        {
            return response()->json([
                'error' => 'SocialiteProviderError'
            ], 500);
        }
    }

    /**
     * @api {post} /auth/:provider/callback         Login user with data from provider.
     * @apiName LoginUser
     * @apiGroup Authentication
     *
     * @apiParam {String} provider                  Provider name
     *
     * @apiSuccess {Object} token                   Token.
     * @apiSuccess {String} token.access_token      Token for API authorization.
     * @apiSuccess {String} token.token_type        Token type.
     * @apiSuccess {Int} token.expires_in           Token TTL (time-to-live) in seconds.
     * @apiUse User
     *
     * @apiError (Error 500) SocialiteProviderError Failed to get authentication URL.
     * @apiError (Error 500) DatabaseError
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handleProviderCallback(ProviderCallbackRequest $request): JsonResponse
    {
        $provider = $request->validated()['provider'];
        try
        {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $userData = [
                'social_provider_id' => $socialUser->getId(),
                'social_provider' => $provider,
                'social_name' => $socialUser->getName(),
                'social_email' => $socialUser->getEmail(),
                'social_avatar' => $socialUser->getAvatar(),
            ];

            Validator::validate($userData, [
                'social_provider_id' => 'numeric',
                'social_name' => 'string|nullable',
                'social_email' => 'string|email',
                'social_avatar' => 'string|nullable',
            ]);

            $result = $this->findOrCreateUser($userData);

            if ($result instanceof Left) {
                return response()->json([
                    'error' => 'DatabaseError'
                ], 500);
            }

            /**
             * @var User
             */
            $user = $result->extract();
            /**
             * @var string $token
             */
            $token = auth()->login($user);

            $socialData = SocialAccount::where([
                ['social_provider_id', $socialUser->getId()],
                ['social_provider', $provider],
            ])->first()->toArray();

            $userData = [
                ...$user->toArray(),
                ...$socialData
            ];

            $this->respondWithToken($token, $userData);
        }
        catch (\InvalidArgumentException $e)
        {
            return response()->json([
                'error' => 'SocialiteProviderError'
            ], 500);
        }
    }

    /**
     * @api {post} /auth/token/refresh          Refresh a token.
     * @apiName RefreshToken
     * @apiGroup Authentication
     *
     * @apiSuccess {Object} token               Token.
     * @apiSuccess {String} token.access_token  Token for API authorization.
     * @apiSuccess {String} token.token_type    Token type.
     * @apiSuccess {Int} token.expires_in       Token TTL (time-to-live) in seconds.
     *
     * @apiError (Error 5xx) ServerError        Unhandled server error.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token and optional user data.
     *
     * @param string $token
     * @param array $data optional
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, array $userData = [])
    {
        return response()->json([
            'token' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
            'user' => $userData
        ], 200);
    }

    /**
     * Find or create user via given credentials.
     *
     * @param array $userData
     * @return Either
     */
    private function findOrCreateUser(array $userData): Either
    {
        try
        {
            $user = null;

            DB::beginTransaction();

            /**
             * @var SocialAccount
             */
            $socialAccount = SocialAccount::firstOrNew(
                [
                    'social_provider' => $userData['social_provider'],
                    'social_provider_id' => $userData['social_provider_id']
                ],
                [
                    'social_name' => $userData['social_name'],
                    'social_avatar' => $userData['social_avatar'],
                ]
            );

            $user = $socialAccount->user() ?? function() use ($userData, $socialAccount) {
                $candidate = User::where('email', $userData['social_email']);
                if ($candidate) {
                    $socialAccount->fill(['user_id' => $candidate->id])->save();
                }
                return $candidate;
            };

            if (!$user) {
                $user = new User;

                $usernameFromEmail = explode('@', $userData['social_email'])[0];
                $foundNicknamesCounter = User::where(
                    'username',
                    'like',
                    '%' . $usernameFromEmail . '%'
                )->count();

                $nicknameWithId = $usernameFromEmail . '#' . $foundNicknamesCounter;

                $user->username = $nicknameWithId;
                $user->email = $userData['social_email'];
                $user->password = '83JvTqXaLLQRPkTf';
                $user->save();

                $role = Role::where('title', 'user')->first();
                $user->roles()->save($role);

                $socialAccount->fill(['user_id' => $user->id])->save();
            }

            DB::commit();

            return Right::of($user);
        }
        catch (\Throwable $e)
        {
            DB::rollBack();
            return Left::of($e);
        }
    }
}
