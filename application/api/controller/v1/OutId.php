<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\OutIdValidate;
use app\common\model\OutId as OutIdModel;
use think\Controller;
use think\Request;

class OutId extends BaseController
{
    //    认证
    public function userAuth(){
        (new OutIdValidate())->goCheck('userAuth');
        $data=(new OutIdModel())->userAuth();
        return self::showResCodeWithOutData('认证成功');
    }
//    查询
    public function checkAuth(){
        $data=(new OutIdModel())->checkAuth();
        return $data?self::showResCode('请先认证钱包',$data):self::showResCode('获取成功',$data);
    }
}
