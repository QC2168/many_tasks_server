<?php

namespace app\common\model;

use think\Model;

class Info extends Model
{
    // 获取邀请码
    public function getInvitation(){

}
    // 获取首页通知信息框
    public function getMessage(){

    }
// 获取首页通知栏数据
    public function getNoticeBar(){
        return $this->where('key','notice_bar')->value('value');
    }
    // 设置首页通知栏数据
    public function setNoticeBar(){
        $message=request()->param('message');
       $this->save(['value'=>$message],['key'=>'notice_bar']);
       return true;
    }
}
