<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\AdminUserValidate;
use app\common\model\AdminUser as AdminUserModel;
use think\Controller;
use think\Request;

class AdminUser extends BaseController
{
    //登录
    public function login(){
        (new AdminUserValidate())->goCheck('login');
        $token=(new AdminUserModel())->login();
        return self::showResCode('登录成功',['token'=>$token]);
    }
}
