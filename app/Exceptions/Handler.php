<?php

namespace App\Exceptions;

use Exception;
use HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
        'password',
        'password_confirmation',
    ];

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
        if ($request->is("api/*"))
        {
            if ($exception instanceof ModelNotFoundException)
            {
                return response()->json(['message' => "La ressource n'existe pas ou a été supprimée"], 404);
            }

            if ($exception instanceof NotFoundHttpException)
            {
                return response()->json(['message' => "La ressource n'existe pas ou a été supprimée"], 404);
            }

            if ($exception instanceof AuthenticationException)
            {
                return response()->json([
                    'message' => "Votre session a expiré, veuillez vous reconnecter"
                ], 401);
            }

            if($exception instanceof TokenInvalidException || $exception instanceof TokenExpiredException)
            {
                return response()->json([
                    'message' => 'Votre session a expiré, veuillez vous reconnecter'
                ], 401);
            }

        }

        return parent::render($request, $exception);
    }
}
