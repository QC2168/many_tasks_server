<?php

namespace app\common\validate;

use think\Validate;

class UserValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'username'=>'require|chsDash|length:1,10',
	    'password'=>'require|chsDash|length:4,16',
	    'rpassword'=>'require|chsDash|length:4,16',
	    'phone'=>'require|mobile|regPhone',
        'code'=>'alphaNum|length:1,6',
        'feedback_content'=>'require',
        'user_pic'=>'file',
        'id'=>'require|number|isUserId',

    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.chsDash'=>"用户名规则不正确",
        'password.chsDash'=>"用户名规则不正确",
    ];

    //场景
    protected $scene=[
        //注册
        'register'=>['username','password','rpassword','phone','code'],
        //账号密码登录
        'login'=>['username','password'],
        //反馈
        'feedback'=>['feedback_content'],
        'uploadUserPic'=>['user_pic'],
        'changeUserStatus'=>['id'],
        'changePassword'=>['rpassword'],
    ];
}
