<?php

namespace App\Exceptions;

use App\Supports\Facades\Response\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson() || $request->ajax()) {
            if ($exception instanceof ValidationException) {
                $errors = array_values($exception->errors());

                return Response::failure($errors[0][0], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($exception instanceof UnauthorizedException) {
                return Response::failure($exception->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);
            }

            if ($exception instanceof SystemException) {
                return Response::failure($exception->getMessage());
            }

            if ($exception instanceof ForbiddenException) {
                return Response::failure($exception->getMessage(), JsonResponse::HTTP_FORBIDDEN);
            }

            if ($exception instanceof NotFoundException) {
                return Response::failure($exception->getMessage(), JsonResponse::HTTP_NOT_FOUND);
            }

            if ($exception instanceof UnprocessableException) {
                return Response::failure($exception->getMessage(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($exception instanceof ResourceConflictException) {
                return Response::failure($exception->getMessage(), JsonResponse::HTTP_CONFLICT);
            }

            if ($exception instanceof AuthenticationDenied) {
                return Response::failure($exception->getMessage(), 416);
            }

            if ($exception instanceof PermissionDeniedException) {
                return Response::failure($exception->getMessage(), 415);
            }
        }

        return parent::render($request, $exception);
    }
}
