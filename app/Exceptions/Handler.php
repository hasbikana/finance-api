<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if (!$request->expectsJson() && !$request->is('api/*')) {
            return parent::render($request, $e);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'data' => $e->errors(),
            ], 422);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Endpoint tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'data' => null,
            ], 401);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Metode HTTP tidak diizinkan untuk endpoint ini.',
                'data' => null,
            ], 405);
        }

        return response()->json([
            'status' => 'error',
            'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server.',
            'data' => null,
        ], $this->getStatusCode($e));
    }

    /**
     * Get status code from exception.
     */
    private function getStatusCode(Throwable $e): int
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        return 500;
    }
}