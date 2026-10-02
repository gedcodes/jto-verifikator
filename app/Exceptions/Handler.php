<?php

namespace App\Exceptions;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->renderable(function (NotFoundHttpException $e, $request) {
            // if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => __('message.DATA_NOTFOUND')
            ], 404);
            // }
        });
    }

    // private function handleApiException($request, Exception $exception)
    // {
    //     $exception = $this->prepareException($exception);

    //     if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
    //         $exception = $exception->getResponse();
    //     }

    //     if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
    //         $exception = $this->unauthenticated($request, $exception);
    //     }

    //     if ($exception instanceof \Illuminate\Validation\ValidationException) {
    //         $exception = $this->convertValidationExceptionToResponse($exception, $request);
    //     }

    //     return $this->customApiResponse($exception);
    // }
}
