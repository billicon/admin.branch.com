<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/24
 * Time: 10:20
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
     public $code      = 403;
     public $msg       = '权限不够';
     public $errorCode = 10001;
}