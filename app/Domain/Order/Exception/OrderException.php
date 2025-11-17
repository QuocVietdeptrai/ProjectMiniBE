<?php

namespace App\Domain\Order\Exception;

use App\Enums\StatusCode;
use Exception;

class OrderException extends Exception
{
    protected $message = 'Lỗi hệ thống đơn hàng';
    protected $code = StatusCode::INTERNAL_ERR;
}