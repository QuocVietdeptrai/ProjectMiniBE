<?php

namespace App\Domain\Product\Exception;

use App\Enums\StatusCode;

class ProductNotFoundException extends ProductException
{
    protected $message = 'Sản phẩm không tồn tại';
    protected $code = StatusCode::NOT_FOUND;
}