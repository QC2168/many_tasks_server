<?php

namespace app\common\model;

use think\Db;
use think\Model;

class RechargeKey extends Model
{
    protected $autoWriteTimestamp = true;

    public function recharge()
    {
        return Db::transaction(function () {
            $cKey = request()->param('cKey');
            // 查找这个key的价格
            $value = $this->where(['key' => $cKey])->value('value');
            $assets = new Assets();
            $assets->where('username', request()->username)->setInc('wallet', $value);
            $this->save(['status' => 0], ['key' => $cKey]);
            add_wallet_details(1,$value,"卡密充值");
        });

    }

    // 创建卡密
    public function createRechargeKey(){
        // 生成卡密数量
        $create_number=request()->param('create_number');
        // 生成卡密的余额
        $create_money=request()->param('create_money');
        // 卡密
        $cKey=[];
        // 创建卡密
        for ($i=0;$i<$create_number;$i++){
            $key_data=[
                'key'=>strtoupper(md5((time()+$i))),
                'value'=>$create_money,
                'status'=>1
            ];
            array_push($cKey,$key_data);
        }
        try {
            $this->saveAll($cKey);
        } catch (\Exception $e) {
            TApiException('创建出错了',20016,200);
        }
        return $cKey;

    }
}
