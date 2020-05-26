<?php

namespace app\common\validate;

use think\Validate;

class OutOrderListValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'amount'=>'number|istenfold',
        'target'=>'number|between:0,2',
        'orderSn'=>'require|isOutOrderSn',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene = [
        'out'=>'amount',
        'changeAOutOrderStatus'=>['target','orderSn'],
    ];
}
