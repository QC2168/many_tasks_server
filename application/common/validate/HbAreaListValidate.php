<?php

namespace app\common\validate;

use think\Validate;

class HbAreaListValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'content'=>'require',
	    'hb_amount'=>'require|between:0,1000',
	    'quota'=>'require|number|between:1,1000',
        'hb_pic_list'=>'require|isArr',
        'hb_id'=>'require|number|isHbId',
        'pic'=>'file',
        // 页数
        'index'=>'require|number'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    protected $scene=[
      // 发布爆粉
        'getHbAreaList'=>['index'],
        'pushHb'=>['content','hb_amount','quota','hb_pic_list'],
'commitComment'=>['content','hb_id'],
'getHbDetail'=>['hb_id'],
'getHbDetailCommentList'=>['hb_id'],
        'uploadHbDetailPic'=>['pic'],

        'deleteHb'  =>  ['hb_id'],

    ];
}
