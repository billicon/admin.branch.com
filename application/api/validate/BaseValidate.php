<?php
namespace app\api\validate;
use think\Validate;
use think\Request;
use think\Exception;
use app\lib\exception\ParameterException;
class BaseValidate extends Validate{
	public function gocheck(){
			//获取http传入的参数
		    //对这些参数进行校验
	    $request = Request::instance();
	    $param = $request->param();
	    $result = $this->batch()->check($param);
	    if(!$result){
	    	$e = new ParameterException(['msg'=>$this->error]);
	    	throw $e;
	    }
	    else{
	    	return true;
	    }
	}
	protected function isPost($value, $rule='', $data='', $field=''){
		if(is_numeric($value) && is_int($value + 0) && ($value + 0) >0){
			return true;
		}
		return false;
	}
	protected function isNotEmpty($value, $rule='', $data='', $field=''){
		if(empty($value)){
			return false;
		}
		return true;
	}
	public function getDataByRule($arrays){
        if(array_key_exists('user_id',$arrays) | array_key_exists('uid',$arrays)){
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id和uid'
            ]);
        }
        $newArray = [];
        foreach($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
    public function isMobile($value){
	    $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
	    $result = preg_match($rule,$value);
	    if($result){
	        return true;
        }else{
	        return false;
        }
    }
}

// 404  资源不存在
// 400  参数错误
// 200  查询get执行成功
// 201  post创建资源成功
// 202  put更新成功
// 401  接口未授权
// 403  当前资源被禁止
// 500  服务器的未知错误