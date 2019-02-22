<?php
namespace app\api\validate;

class IdCollection extends BaseValidate{
	protected $rule=[
		'ids' => 'require|checkIDS'
	];
	protected $message=[
		 'ids' => 'ids必须是以逗号分割的正整数'
	];

	protected function checkIDS($value, $rule='', $data='', $field=''){
		$values = explode(',',$value);
		if(empty($values)){
			return false;
		}
		foreach($values as $id){
			if(!$this->isPost($id)){
				return false;
			}
		}
		return true;
	}
}