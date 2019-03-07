<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/22
 * Time: 17:22
 */
namespace app\api\validate;

 class AddressNew extends BaseValidate {
     protected $rule = [
        'name'     => 'require|isNotEmpty',
        'mobile'   => 'require|isNotEmpty',
        'province' => 'require|isNotEmpty',
        'city'     => 'require|isNotEmpty',
        'country'  => 'require|isNotEmpty',
        'detail'   => 'require|isNotEmpty',
     ];
 }