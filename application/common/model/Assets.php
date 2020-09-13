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
        $bind=OutId::where(['username'=> request()->username])->value('zfb');
        return ['assets'=>$assets,'bind'=>$bind];
    }
}
