<?php

namespace app\common\validate;

use think\Validate;

class RewardTaskListValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title'=>'require|chsDash|length:1,15',
        'reward_goods_platform_type'=>'require|isRewardGoodsPlatformType',
        'reward_goods_type'=>'require|isRewardGoodsType',
        'content'=>'require',
        'price'=>'require',
        'goods_url'=>'require|url',
        'liaison'=>'require',
        'quota'=>'require|number|between:1,300',
        'reward_task_id'=>'require|number',
        'reward_task_step_list'=>'require|isArr',
        'is_btn'=>'require|between:0,1',
        'keyword'=>'require',
        'pic'=>'file'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    protected $scene = [
        'pushRewardTask'  =>  ['title','reward_goods_platform_type','reward_goods_type','content','goods_url','liaison','price','quota','is_btn','reward_task_step_list','keyword'],
        'getRewardTaskDetail'  =>  ['reward_task_id'],
        'getTaskOrderInfo'  =>  ['task_id'],
        'deleteTask'  =>  ['task_id'],
        'uploadRewardTaskDetailPic'=>['pic']
    ];
}
