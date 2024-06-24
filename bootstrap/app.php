<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->renderable(function (ValidationException $e) {
            return response()->json([
               'message' => $e->errors()
            ], 403);
        })->renderable(function (NotFoundHttpException $e) {
            $message = "Route does not exist";
            if ($e->getPrevious() && $e->getPrevious() instanceof ModelNotFoundException){
                $message = "Record not found";
            }
            return response()->json([
                'message' => $message
             ], 404);
            
        });
      
    })->create();
