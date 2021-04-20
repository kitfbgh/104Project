<?php

namespace App\Exceptions;

use Exception;

class APIException extends Exception
{
    /**
     * APIException constructor.
     * @param string $message
     * @param string $code
     */
    public function __construct(
        $message = 'API 回傳錯誤，請洽相關人員',
        $code = 500
    ) {
        parent::__construct($message, $code);
    }
}
