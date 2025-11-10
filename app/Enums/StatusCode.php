<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StatusCode extends Enum
{
    public const OK = 200;

    public const CREATED = 201;

    public const NO_CONTENT = 204;

    public const NOT_MODIFIED = 304;

    public const BAD_REQUEST = 400;

    public const UNAUTHORIZED = 401;

    public const FORBIDDEN = 403;

    public const NOT_FOUND = 404;

    public const METHOD_NOT_ALLOWED = 405;

    public const PERMISSION_DENIED = 406;

    public const CONFLICT = 409;

    public const GONE = 410;

    public const UNSUPOPPER_MEDIA_TYPE = 415;

    public const UNPROCESSABLE_ENTITY = 422;

    public const TOO_MANY_REQUEST = 429;

    public const PARAMS_ERR = 600;

    public const INTERNAL_ERR = 500;

    public const ERROR = 'error';

    public const SUCCESS = 'success';

    public const WARNING = 'warning';

    public const A1V = 408;
}
