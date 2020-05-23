<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use think\Request;
use app\common\model\Assets as AssetsModel;
use app\common\model\WalletDetails as WalletDetailsModel;

class Assets extends BaseController
{
   public function assets(){
       $Assest=(new AssetsModel())->assets();
       return self::showResCode('获取成功',$Assest);
   }
    public function getUserWalletDetails(){
        $WalletDetails=(new WalletDetailsModel())->getUserWalletDetails();
        return self::showResCode('获取成功',$WalletDetails);
    }
}
