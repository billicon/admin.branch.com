<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/24
 * Time: 12:04
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];
    protected $singleRule = [
        'product_id' => 'require|isPost',
        'count'      => 'require|isPost'
    ];
    protected function checkProducts($values){
        if(!is_array($values)){
            throw new ParameterException([
                'msg' => '商品参数不正确'
            ]);
        }
        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach($values as $value){
            $this->checkProduct($value);
        }
        return true;
    }
    protected function checkProduct($value){
        $validate = new BaseValidate($this->singleRule);
        $result   = $validate->check($value);
        if(!result){
            throw new ParameterException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }
}