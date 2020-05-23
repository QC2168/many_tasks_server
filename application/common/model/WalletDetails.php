<?php

namespace app\common\model;

use think\Model;

class WalletDetails extends Model
{
    protected $autoWriteTimestamp = true;

    // 获取用户钱包详情
    public function getUserWalletDetails()
    {
       return $this->where('username',request()->username)->order(['create_time'=>'desc'])->select();
    }
}
