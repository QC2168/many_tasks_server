<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use think\Request;
use app\common\model\Sign as SignModel;
class Sign extends BaseController
{
public function sign(){
    $data=(new SignModel())->sign();
    return self::showResCode('签到成功',$data);
}
    public function signData(){
        $list=(new SignModel())->signData();
        return self::showResCode('获取成功',$list);
    }
}
