<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/23
 * Time: 11:02
 */
namespace app\lib\exception;

class SuccessMessage extends BaseException{
    public $code = 201;
    public $msg  = 'ok';
    public $errorCode = 0;
}