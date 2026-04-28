<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->redirectGuestsTo(function (Request $request) {
            return route('Unauthorized');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (AuthenticationException $e, $request) {
            $response['statusCode'] = 401;
            $response['message'] = 'Unauthorized';
            return response()->json(['message' => 'Unauthorized'],401);
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json(['message' => $e->getMessage()],405);
        });
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json(['message' => $e->getMessage()],404);
        });
    })->create();
