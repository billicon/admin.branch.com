<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/18
 * Time: 18:54
 */
namespace app\lib\exception;
use think\Exception;

/**
 * 微信服务器异常
 */
class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}