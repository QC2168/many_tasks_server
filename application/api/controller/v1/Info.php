<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\Version;
use think\Cache;
use think\Controller;
use think\Request;
use app\common\model\HomePagePic as HomePagePicModel;
use app\common\model\NoticeBar as NoticeBarModel;
use app\common\model\User as UserModel;

class Info extends BaseController
{
   // 获取轮播图
    public function getHomePic(){
        $list=(new HomePagePicModel())->getHomePic();
        return self::showResCode('获取成功',$list);
    }

    // 获取NoticeBar
    public function getNoticeBar(){
        $list=(new NoticeBarModel())->getNoticeBar();
        return self::showResCode('获取成功',$list);
    }

    // 检查版本
    public function updateV(){
        $list=(new Version())->updateV();
        return self::showResCode('获取成功',$list);
    }
    // 获取 后台首页数据
    public function wmsHomeData(){
        $list=(new UserModel())->wmsHomeData();
        return self::showResCode('获取成功',$list);

    }
}
