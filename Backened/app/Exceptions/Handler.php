<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    "success" => false,
                    'errors' => [$e->getMessage(),]
                ], $e->getStatusCode());
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            $errors = [];
            foreach ($e->errors() as $ers) {
                foreach ($ers as $error) {
                    $errors[] = $error;
                }
            }
            if ($request->is('api/*')) {
                return response()->json([
                    "success" => false,
                    'errors' => $errors
                ], $e->status ?? 500);
            }
        });
        $this->renderable(function (AuthenticationException $e, Request $request) {
            return response()->json([
                "success" => false,
                'errors' => [
                    "Unauthenticated." === $e->getMessage() ? "Authentication failed! Please login first" : $e->getMessage()
                ]
            ], 401);
        });
    }
    protected function shouldReturnJson($request, Throwable $e)
    {
        return (bool) ($request->is('api/*'));
    }
}
