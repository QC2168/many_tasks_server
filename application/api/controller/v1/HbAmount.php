<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use app\common\model\HbAmount as HbAmountModel;
use think\Request;

class HbAmount extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function getAmountList()
    {
       $data=(new HbAmountModel())->getAmountList();
       return self::showResCode('获取成功',$data);

    }


}
