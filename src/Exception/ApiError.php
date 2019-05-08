<?php

namespace VcAsh\Exception;

use Throwable;

class ApiError extends \Exception
{
    public function __construct($message='', $code=0, Throwable $previous=null) {
        $msg = 'Api Error';
        $msg .= ($code !== 0 ? ' '.$code : '');
        $msg .= ': ';
        $msg .= $message;
        parent::__construct($msg, $code, $previous);
    }

}