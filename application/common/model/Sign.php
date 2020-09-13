<?php

namespace app\common\model;

use think\Db;
use think\Model;

class Sign extends Model
{

    protected $autoWriteTimestamp = true;
    // 签到
    public function sign(){
       return Db::transaction(function () {
           // 判断今天是否完成了悬赏任务 / 抖音任务
          $TaskOrder= new TaskOrder();
           $DyTaskOrder= new DyTaskOrder();
           $todayDyTaskOrder=$DyTaskOrder->where(['username'=>request()->username,'status'=>1])->whereTime('create_time', 'today')->find();
           $todayTaskOrder= $TaskOrder->where(['username'=>request()->username,'status'=>1])->whereTime('create_time', 'today')->find();
           if(empty($todayDyTaskOrder) && empty($todayTaskOrder))TApiException('请完成任意一个任务再来签到',20012,200);
           // 获取用户钱包
           $userCurrentWallet=Assets::where(['username'=>request()->username])->value('wallet');
        // 判断用户今天是否签到了
        // 获取最后签到时间
        $last_time=$this->where(['username'=>request()->username])->value('last_time');
// 当天的零点
        $dayBegin = strtotime(date('Y-m-d', time()));
// 当天的24
        $dayEnd =strtotime("-1 second",$dayBegin + 24 * 60 * 60);

        // 判断最后这个签到时间是不是今天
        if($last_time >= $dayBegin && $last_time <= $dayEnd)return TApiException('今天已经签到过了',20013,200);
            // 不是今天  签到
            // 生成现在时间搓
            $currentTime=time();
            $this->save(['last_time'=>$currentTime],['username'=>request()->username]);
            // 判断昨天有没有签到
            // 昨天的零点
            $YesterdayBegin = strtotime("-1 day",$dayBegin);
            // 昨天的24
            $YesterdayEnd=strtotime("-1 day",$dayEnd);
            if($last_time >= $YesterdayBegin && $last_time <= $YesterdayEnd){
                // 连续签到

                $this->where(['username'=>request()->username])->setInc('continued',1);
                // 签到福利
                $assets=new Assets();
                $addPrice=(random_int(1,3)*0.1)/2;
                $assets->where(['username'=>request()->username])->setInc('wallet',$addPrice);
                $signLog=new SignLog();
                $signLog->create([
                   'username'=>request()->username,
                   'price'=>$addPrice
                ]);
                add_wallet_details(1,$addPrice,'签到奖励');
                return "签到成功，本次获得{$addPrice}元";
            }else{
                // 第一次签到 或者是断签了
                $assets=new Assets();
                $this->save(['continued'=>1],['username'=>request()->username]);
                $randomPrice=(random_int(1,4)*0.1)/2;

                $assets->where(['username'=>request()->username])->setInc('wallet',$randomPrice);
                $signLog=new SignLog();
                $signLog->create([
                    'username'=>request()->username,
                    'price'=>$randomPrice
                ]);
                add_wallet_details(1,$randomPrice,'签到奖励');
                return "签到成功，本次获得{$randomPrice}元";
            }


        });

    }
    public function signData(){

        // 判断签到了没
        // 获取最后签到时间
        $last_time=$this->where(['username'=>request()->username])->value('last_time');
// 当天的零点
        $dayBegin = strtotime(date('Y-m-d', time()));
// 当天的24
        $dayEnd =strtotime("-1 second",$dayBegin + 24 * 60 * 60);
        $isSign=(($last_time >= $dayBegin) && ($last_time <= $dayEnd))?1:0;
        // 获取连续签到次数
        $continued=$this->where(['username'=>request()->username])->value('continued');
        return ['continued'=>$continued,'isSign'=>$isSign];
    }
}
