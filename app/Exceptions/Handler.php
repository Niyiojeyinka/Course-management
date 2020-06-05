<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Validation\ValidationException,
    Illuminate\Database\Eloquent\ModelNotFoundException,
    Illuminate\Auth\AuthenticationException,
    Illuminate\Auth\Access\AuthorizationException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Symfony\Component\HttpKernel\Exception\HttpException,
    Symfony\Component\Routing\Exception\RouteNotFoundException,
    Illuminate\Database\QueryException;

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
    protected $dontFlash = ['password', 'password_confirmation'];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(
                [
                    'result' => 0,
                    'error' => $errors,
                    'data' => [],
                ],
                422
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return response()->json(
                [
                    'result' => 0,
                    'error' => $model . ' not found',
                    'data' => [],
                ],
                404
            );
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(
                [
                    'result' => 0,
                    'error' => 'Unautheticated',
                    'data' => [],
                ],
                401
            );
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(
                [
                    'result' => 0,
                    'error' => $exception->getMessage(),
                    'data' => [],
                ],
                403
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(
                [
                    'result' => 0,
                    'error' => 'This URL does not exist',
                    'data' => [],
                ],
                404
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(
                [
                    'result' => 0,
                    'error' =>
                        'The specified method for this request is not allowed',
                    'data' => [],
                ],
                405
            );
        }

        if ($exception instanceof RouteNotFoundException) {
            return response()->json(
                [
                    'result' => 0,
                    'error' => $exception->getMessage(),
                    'data' => [],
                ]
                //$exception->getStatusCode()
            );
        }

        if ($exception instanceof HttpException) {
            return response()->json(
                [
                    'result' => 0,
                    'error' => $exception->getMessage(),
                    'data' => [],
                ],
                $exception->getStatusCode()
            );
        }

        return parent::render($request, $exception);
    }

    /* protected function convertValidationExceptionToResponse(
        ValidationException $e,
        $request
    ) {
        $errors = $e->validator->errors()->getMessages();

        return response()->json($errors, 422);
    }*/
}