<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/18
 * Time: 17:46
 */

 namespace app\lib\exception;
 use think\Exception;

 /**
  * 微信服务器异常
  */
 class WeChatException extends BaseException
 {
     public $code = 400;
     public $msg = 'wechat unknown error';
     public $errorCode = 999;
 }