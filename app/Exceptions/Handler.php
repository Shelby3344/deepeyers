<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * ✅ NUNCA flasha estes campos sensíveis para a sessão
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'token',
        'api_key',
        'secret',
        'credit_card',
        'cvv',
        'ssn',
        'authorization',
    ];

    /**
     * Exceções que não devem ser reportadas
     */
    protected $dontReport = [
        ValidationException::class,
        NotFoundHttpException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log de segurança para erros críticos
            if ($this->shouldLogSecurityEvent($e)) {
                Log::channel('security')->error('Security exception', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'ip' => request()->ip(),
                    'path' => request()->path(),
                    'user_id' => auth()->id(),
                ]);
            }
        });

        // ✅ Respostas seguras para DeepSeek errors
        $this->renderable(function (DeepSeekException $e, $request) {
            if ($request->expectsJson()) {
                return $this->safeJsonResponse(
                    'AI Service Error',
                    $e->getMessage(),
                    $this->safeStatusCode($e->getCode())
                );
            }
        });

        // ✅ Respostas genéricas para 404 (não revela estrutura)
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'The requested resource was not found.',
                ], 404);
            }
        });

        // ✅ Respostas genéricas para erros HTTP
        $this->renderable(function (HttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->safeJsonResponse(
                    'Error',
                    $this->getSafeMessage($e),
                    $e->getStatusCode()
                );
            }
        });

        // ✅ Handler genérico para erros não tratados (NUNCA expor stack trace)
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() && !app()->isLocal()) {
                // Em produção, NUNCA expor detalhes do erro
                return response()->json([
                    'error' => 'Server Error',
                    'message' => 'An unexpected error occurred.',
                    'request_id' => uniqid('err_'),
                ], 500);
            }
        });
    }

    /**
     * Verifica se o erro deve ser logado como evento de segurança
     */
    private function shouldLogSecurityEvent(Throwable $e): bool
    {
        $securityExceptions = [
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class,
            \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException::class,
        ];

        foreach ($securityExceptions as $class) {
            if ($e instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retorna resposta JSON segura
     */
    private function safeJsonResponse(string $error, string $message, int $status): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'message' => $message,
        ], $status);
    }

    /**
     * Garante que o status code é válido
     */
    private function safeStatusCode(int $code): int
    {
        return ($code >= 400 && $code < 600) ? $code : 500;
    }

    /**
     * Retorna mensagem segura sem expor detalhes internos
     */
    private function getSafeMessage(HttpException $e): string
    {
        $safeMessages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
        ];

        return $safeMessages[$e->getStatusCode()] ?? 'An error occurred';
    }
}
