<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-01-31
 * Time: 14:08
 */

namespace app\lib\exception;


class UnionException extends BaseException
{
    public $code = 404;
    public $msg = 'unionID查询错误';
    public $errorCode = 2;
}