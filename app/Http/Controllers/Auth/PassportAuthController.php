<?php

namespace App\Http\Controllers\Auth;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\TokenRepository;
use Psr\Http\Message\ServerRequestInterface;



class PassportAuthController extends AccessTokenController
{
    public function __construct(AuthorizationServer $server, TokenRepository $tokens)
    {
        parent::__construct($server, $tokens);
    }

    public function login(ServerRequestInterface $request)
    {
        $parsedBody = $request->getParsedBody();
        $parsedBody['username'] = $parsedBody['email'];
        $parsedBody['grant_type'] = 'password';
        $parsedBody['client_id'] = env("PASSPORT_PASSWORD_ACCESS_CLIENT_ID");
        $parsedBody['client_secret'] = env("PASSPORT_PASSWORD_ACCESS_CLIENT_SECRET");

        // since it is not required by passport
        unset($parsedBody['email']);

        return parent::issueToken($request->withParsedBody($parsedBody));
    }

    public function loginEcommerce(ServerRequestInterface $request)
    {
        $parsedBody = $request->getParsedBody();
        $parsedBody['username'] = $parsedBody['email'];
        $parsedBody['grant_type'] = 'password';
        $parsedBody['client_id'] = env("PASSPORT_ECOMMERCE_PASSWORD_ACCESS_CLIENT_ID");
        $parsedBody['client_secret'] = env("PASSPORT_ECOMMERCE_PASSWORD_ACCESS_CLIENT_SECRET");
        // since it is not required by passport
        unset($parsedBody['email']);

        return parent::issueToken($request->withParsedBody($parsedBody));
    }

    public function loginMobileApp(ServerRequestInterface $request)
    {
        $parsedBody = $request->getParsedBody();
        $parsedBody['username'] = $parsedBody['email'];
        $parsedBody['grant_type'] = 'password';
        $parsedBody['client_id'] = env("PASSPORT_MOBILE_APP_PASSWORD_ACCESS_CLIENT_ID");
        $parsedBody['client_secret'] = env("PASSPORT_MOBILE_APP_PASSWORD_ACCESS_CLIENT_SECRET");
        // since it is not required by passport
        unset($parsedBody['email']);

        return parent::issueToken($request->withParsedBody($parsedBody));
    }

}