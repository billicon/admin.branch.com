<?php
namespace app\lib\exception;
use \Exception;
use think\exception\Handle;
use think\Request;
use think\Log;
class ExceptionHandle extends Handle{
	private $code;
	private $msg;
	private $errorCode;
	//需要返回客户端当前请求的url
    public function render(Exception $e){
    	if($e instanceof BaseException){
    		$this->code      = $e->code;
    		$this->msg       = $e->msg;
    		$this->errorCode = $e->errorCode;
    	}else{
    		 if(config('app_debug'))
    		 {
    		 	 return parent::render($e);
    		 }
    		 else
    		 {
    		 	 $this->code = 500;
	    		 $this->msg  = $e->msg;
	    		 $this->errorCode = 999;
	    		 $this->recordErrorLog($e);
    		 }
    	}
    	$request = Request::instance();
    	$result = [
    		'msg'         => $this->msg,
    		'error_code'  => $this->errorCode,
    		'request_url' => $request->url()
    	];
    	return json($result,$this->code);
    }
    private function recordErrorLog(Exception $e){
    	Log::init([
    			'type' => 'File',
    			'path' => LOG_PATH,
    			'level'=> ['error']
    	]);
    	Log::record($e->getMessage(),'error');
    }
}