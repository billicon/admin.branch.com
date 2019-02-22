<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-01-30
 * Time: 18:49
 */

namespace app\api\service;


use app\lib\exception\WeChatException;
use think\Exception;

class UserToken
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $exLoginUrl;
    public function __construct($code)
    {
        $this->code  = $code;
        $this->wxAppID= config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->exLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }
    public function get(){
        $result = file_get_contents($this->exLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('error',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $wxResult;
            }
        }
    }
    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}