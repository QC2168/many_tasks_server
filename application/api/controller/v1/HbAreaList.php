<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\HbAreaListValidate;
use \app\common\model\HbAreaList as HbAreaListModel;
use \app\common\model\HbAreaCommentList as HbAreaCommentListModel;
use \app\common\model\HbTopSelectList as HbTopSelectListModel;
use think\Controller;
use think\Request;

class HbAreaList extends BaseController
{

    public function getHbAreaList()
    {
        (new HbAreaListValidate())->goCheck('getHbAreaList');
        $data=(new HbAreaListModel())->getHbAreaList();
        return self::showResCode('获取成功',$data);
    }
    public function getHbDetail()
    {
        (new HbAreaListValidate())->goCheck('getHbDetail');
        $data=(new HbAreaListModel())->getHbDetail();
        return self::showResCode('获取成功',$data);
    }
    public function pushHb()
    {
        (new HbAreaListValidate())->goCheck('pushHb');
       (new HbAreaListModel())->pushHb();
        return self::showResCodeWithOutData('发布成功');
    }
    public function commitComment(){
        (new HbAreaListValidate())->goCheck('commitComment');
        $data=(new HbAreaCommentListModel())->commitComment();
        return self::showResCodeWithOutData($data);
    }
    public function getHbDetailCommentList(){
        (new HbAreaListValidate())->goCheck('getHbDetailCommentList');
        $data=(new HbAreaCommentListModel())->getHbDetailCommentList();
        return self::showResCode('获取成功',$data);
    }
    public function myHb(){
        $data=(new HbAreaListModel())->myHb();
        return self::showResCode('获取成功',$data);
    }
    // 删除广告
    public function deleteHb(){
        (new HbAreaListValidate())->goCheck('deleteHb');
        $data=(new HbAreaListModel())->deleteHb();
        return self::showResCode('删除成功',$data);
    }
    // 置顶
    public function topHb(){
        (new HbAreaListValidate())->goCheck('topHb');
        $data=(new HbAreaListModel())->topHb();
        return self::showResCodeWithOutData('置顶成功');
    }
    // 获取置顶列表
    public function getHbTopList(){
        $data=(new HbTopSelectListModel())->getHbTopList();
        return self::showResCode('获取成功',$data);
    }

    // 上传红包图片图接口
    public function uploadHbDetailPic()
    {
        (new HbAreaListValidate())->goCheck('uploadHbDetailPic');
        $pic = request()->file('pic');
        $info = $pic->validate(['size' => 5242880, 'ext' => 'jpg,png,gif'])->move('../public/static/HbPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/HbPic/'.$getSaveName);
    }

}
