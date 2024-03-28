<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * A description of the entire PHP function.
     *
     * @param datatype $request description
     * @param Throwable $exception description
     * @throws TokenMismatchException description of exception
     * @throws NotFoundHttpException description of exception
     * @throws ModelNotFoundException description of exception
     */
    public function render($request, Throwable $exception)
    {   
        // if ($exception instanceof TokenMismatchException) {
        //     if ($request->expectsJson()) {
        //         return response()->json(['error' => 'CSRF token mismatch'], 401);
        //     } else {
        //         return redirect()->route('login.index')->withErrors(['loginError' => 'Please login again']);
        //     }
        // }
        
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return redirect()->route('login.index')->withErrors(['loginError' => 'Please login again']);
        }

        if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            return response()->view('page-error', [], 404);
        }
        
        return parent::render($request, $exception);
        
    }


}
