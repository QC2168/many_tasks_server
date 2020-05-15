<?php

namespace app\common\model;

use think\Db;
use think\Model;

class RechargeKey extends Model
{
    protected $autoWriteTimestamp = true;
public function recharge(){
    return Db::transaction(function () {
        $cKey=request()->param('cKey');
        // 查找这个key的价格
        $value= $this->where(['key'=>$cKey])->value('value');
        $assets=new Assets();
        $assets->where('username',request()->username)->setInc('wallet',$value);
        $this->save(['status'=>0],['key'=>$cKey]);
    });

}
}
