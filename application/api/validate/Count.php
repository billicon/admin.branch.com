<?php
namespace app\api\validate;

class Count extends BaseValidate{
	protected $rule = [
		'count' => 'isPost|between:1,15'
	];
}