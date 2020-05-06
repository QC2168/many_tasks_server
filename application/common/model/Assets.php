<?php

namespace app\common\model;

use think\Model;

class Assets extends Model
{
    protected $autoWriteTimestamp = true;
    // 获取用户资产
    public function assets()
    {
        $assets=$this->where('username', request()->username)->hidden(['username'])->find();
        $bind=request()->userTokenUserInfo['phone'];
        return ['assets'=>$assets,'bind'=>$bind];
    }
}
