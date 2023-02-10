<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    use ApiResponseTrait;

    public function render($request, Throwable $exception)
    {
        //資料內無對應資料
        // if (1 == 1) {
        if ($request->expectsJson()) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->errorResponse(
                    '找不到資源',
                    Response::HTTP_NOT_FOUND
                );
            }
            //網址uri錯誤
            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse(
                    '找不到此網址',
                    Response::HTTP_NOT_FOUND
                );
            }
            //將不符合的http請求拋出
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse(
                    $exception->getMessage(),
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        //執行父類別 render
        return parent::render($request, $exception);
    }
}
