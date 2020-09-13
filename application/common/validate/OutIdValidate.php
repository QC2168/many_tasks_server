<?php

namespace app\common\validate;

use think\Validate;

class OutIdValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'zfb'=>'require|length:1,15',
        'name'=>'require|chs'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'zfb.between'=>'支付宝账户格式有误',
        'name.chs'=>'姓名只能是中文字符'
    ];
    protected $scene=[
      'userAuth'=>['zfb','name']
    ];
}
