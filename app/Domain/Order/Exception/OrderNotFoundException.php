<?php

namespace App\Domain\Order\Exception;

use App\Enums\StatusCode;

class OrderNotFoundException extends OrderException
{
    protected $message = 'Đơn hàng không tồn tại';
    protected $code = StatusCode::NOT_FOUND;
}