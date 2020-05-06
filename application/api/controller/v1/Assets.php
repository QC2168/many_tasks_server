<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use think\Request;
use app\common\model\Assets as AssetsModel;

class Assets extends BaseController
{
   public function assets(){
       $Assest=(new AssetsModel())->assets();
       return self::showResCode('获取成功',$Assest);
   }
}
