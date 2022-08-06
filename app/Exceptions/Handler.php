<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use App\Http\Resources\ErrorResponse;
use App\Exceptions\V1\HttpRouteException;
use App\Exceptions\V1\RequestValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
         dd($e);
        if (app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }

        if ($e instanceof BaseException) {
            $res = new ErrorResponse($e);
            return response()->json($res, $e->getCode());
        } elseif ($e instanceof HttpException) {
            throw HttpRouteException::routeNotFound();
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            throw HttpRouteException::methodNotAllowed();
        } elseif ($e instanceof ValidationException) {
            $key = array_key_first($e->response->original);
            throw RequestValidationException::errorMessage($e->response->original[$key][0]);
        } else {
            $e = new \Exception(
                'Something went wrong and we have been notified about the problem',
                '500'
            );
            $res = new ErrorResponse($e);
            return response()->json($res, 500);
        }

        return parent::render($request, $e);
    }
}
