<?php
namespace app\api\validate;
class IDmustBePositiveInt extends BaseValidate{
		protected $rule = [
			'id' =>'require|isPost'
		];
		protected $message = [
			'id' =>'id必须是正整数'
		];
	
}