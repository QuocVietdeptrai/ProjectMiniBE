<?php

namespace App\Exceptions\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Throwable;

class HttpException extends SymfonyHttpException
{
    /**
     * @param int $statusCode HTTP status code (ví dụ: 401, 403, 404...)
     * @param string|null $message Thông điệp lỗi
     * @param int $code Mã lỗi tùy chỉnh (khác với HTTP status)
     * @param Throwable|null $previous Exception trước đó
     * @param array $headers HTTP headers bổ sung
     */
    public function __construct(
        int $statusCode,
        ?string $message = null,
        int $code = 0,
        ?Throwable $previous = null,
        array $headers = []
    ) {
        // Nếu không có message, dùng message mặc định của Symfony
        if ($message === null || $message === '') {
            $message = Response::$statusTexts[$statusCode] ?? 'Error';
        }

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}