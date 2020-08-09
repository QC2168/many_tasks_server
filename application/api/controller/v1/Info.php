<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\Version;
use app\common\validate\InfoValidate;
use think\Cache;
use think\Controller;
use think\Request;
use app\common\model\HomePagePic as HomePagePicModel;
use app\common\model\HbAreaNoticeBar as HbAreaNoticeBarModel;
use app\common\model\Info as InfoModel;
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
        $data=(new InfoModel())->getNoticeBar();
        return self::showResCode('获取成功',$data);
    }
    // 获取NoticeBar
    public function getHbAreaNoticeBar(){
        $data=(new HbAreaNoticeBarModel())->getHbAreaNoticeBar();
        return self::showResCode('获取成功',$data);
    }
    // 设置NoticeBar
    public function setNoticeBar(){
        (new InfoValidate())->goCheck('setNoticeBar');
        $data=(new InfoModel())->setNoticeBar();
        return self::showResCodeWithOutData('设置成功');
    }

    // 检查版本
    public function updateV(){
        $data=(new Version())->updateV();
        return self::showResCode('success',$data);
    }
    // 获取 后台首页数据
    public function wmsHomeData(){
        $list=(new UserModel())->wmsHomeData();
        return self::showResCode('获取成功',$list);
    }
}
