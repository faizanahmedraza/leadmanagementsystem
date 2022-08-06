<?php


namespace App\Http\Services\V1;

use Illuminate\Support\Facades\DB;

class AuthenticationService
{
    public function createToken($user)
    {
        $tokenResult = $user->createToken('Password Grant Client');
        $token = $tokenResult->token;
        $token->expires_at = (new \DateTime('now'))->modify("+10 day");
        $token->save();

        return $tokenResult;
    }

    public function generateVerificationResponse($auth, $user)
    {
        return [
            'access_token' => $auth['token']->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => ($auth['token']->token->expires_at)->format('Y-m-d H:i:s'),
            'user' => $user,
            'permissions' => $user->getAllPermissions(),
        ];
    }

    public static function revokeUserToken($user)
    {
        return DB::table('oauth_access_tokens')->where('user_id', '=', $user->id)->update(['revoked' => true]);
    }
}
