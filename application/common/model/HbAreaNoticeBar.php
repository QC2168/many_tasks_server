<?php

namespace app\common\model;

use think\Model;

class HbAreaNoticeBar extends Model
{
    protected $autoWriteTimestamp = true;
    // 获取红包区通知栏数据
    public function getHbAreaNoticeBar(){
        return $this->where(['status'=>1])->select();
    }


}
