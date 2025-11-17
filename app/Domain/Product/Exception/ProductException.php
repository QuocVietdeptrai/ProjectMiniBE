<?php

namespace App\Domain\Product\Exception;

use App\Enums\StatusCode;
use Exception;

class ProductException extends Exception
{
    protected $message = 'Lỗi hệ thống sản phẩm';
    protected $code = StatusCode::INTERNAL_ERR;
}