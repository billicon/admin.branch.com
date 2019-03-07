<?php
namespace app\lib\exception;
use think\Exception;
class BaseException extends Exception{
	//HTTP 状态码200 400
	public $code = 400;
	//错误具体信息
	public $msg = '参数错误';
	//自定义错误码
	public $errorCode = 10000;
	public function __construct($params = []){
		if(!is_array($params)){
		    $e = new Exception();
		    $e->msg = '参数必须是数组';
		    throw $e;
		}
		if(array_key_exists('code',$params)){
			$this->code = $params['code'];
		}
		if(array_key_exists('msg',$params)){
			$this->msg = $params['msg'];
		}
		if(array_key_exists('errorCode',$params)){
			$this->errorCode = $params['errorCode'];
		}
	}
}