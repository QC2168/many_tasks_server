<?php

namespace app\common\model;

use think\Model;

class NoticeBar extends Model
{
public function getNoticeBar(){
    return $this->where('status',1)->select();
}
}
