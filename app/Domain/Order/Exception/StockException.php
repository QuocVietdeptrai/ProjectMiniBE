<?php


namespace App\Domain\Order\Exception;

use App\Enums\StatusCode;

class StockException extends OrderException
{
    protected $message = 'Sản phẩm không đủ trong kho';
    protected $code = StatusCode::BAD_REQUEST;
}