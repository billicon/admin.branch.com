<?php
namespace app\api\validate;
use think\Validate;
class TokenGet extends BaseValidate{
		protected $rule = [
			'code'=>'require'];
			
}