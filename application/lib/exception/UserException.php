<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/23
 * Time: 10:12
 */
namespace app\lib\exception;

class UserException extends BaseException
{
    public $code = 404;
    public $msg  = '用户不存在';
    public $errorCode = 60000;
}