<?php

namespace app\common\model;

use think\Model;

class Version extends Model
{
public function updateV(){
    // 获取版本
    $v=request()->param('v');
    // 获取最新
    $res = $this->order('create_time','desc')->find();
    // 无记录
    if (!$res) TApiException('暂无更新版本');
    if ( $res['version'] == $v ) TApiException('暂无更新版本',20011,200);
    return $res;
}
}
