<?php

namespace app\common\model;

use think\Model;

class HbAreaCommentList extends Model
{
    protected $autoWriteTimestamp = true;
    public function User()
    {
        return $this->hasOne('User','username','username');
    }
    public function commitComment(){
        $param = request()->param();
        //创建评论
        $hb=$this->create([
            'username' => request()->username,
            'content' => $param['content'],
            'show'=>1,
            'get_hb'=>123,
            'hb_id'=>$param['hb_id'],
        ]);
        // 插入通知
        $HbAreaNoticeBar=new HbAreaNoticeBar();
        $HbAreaNoticeBar->save(['username'=>request()->username,'status'=>1,'content'=>'用户 '.substr(request()->username,0,2).'*** 获得评论红包']);
        // 判断之前是否已经领取过红包
        // 获取列表数据
        $curentList=$this->where(['hb_id'=>$param['hb_id'],'username'=>request()->username])->find();
        // 判断之前是否已经领取过红包
        if($curentList!==null) return "评论成功";
        // 查，这个评论是不是免费的
        $HbAreaList=new HbAreaList();
        $_amount=$HbAreaList->where('hb_id',$param['hb_id'])->value('hb_amount');
        if($_amount==0){
            $money=(random_int(1,3)*.1);
            Assets::where('username',request()->username)->setInc('wallet',$money );
            add_wallet_details(1,$money,"悬赏红包");
            return $money;
        }else{
            // 不是免费红包
            // 取剩下的红包金额
            $remainMoney=$HbAreaList->where('hb_id',$param['hb_id'])->value('remaining_hb_amount');
            $remainQuota=$HbAreaList->where('hb_id',$param['hb_id'])->value('remaining_quota');
            // 如果名额剩下一个就直接发放
            if($remainQuota == 1){
                $money=$remainMoney;
                Assets::where('username',request()->username)->setInc('wallet',$money );
                add_wallet_details(1,$money,"悬赏红包");
                 //名额减一
                $HbAreaList->where('hb_id',$param['hb_id'])->setDec('remaining_quota', 1);
                // 清空红包金额 //下架
                //'show'=>0
                $HbAreaList->save(['remaining_hb_amount'=>0],['hb_id'=>$param['hb_id']]);
                return '评论成功,本次获得红包'.$money.'元';
            }else if($remainQuota > 1){
                // 还有多个名额
                //取剩下的红包金额，取随机数
                $money=(random_int(1,($remainMoney/2))*.5);
                Assets::where('username',request()->username)->setInc('wallet',$money );
                add_wallet_details(1,$money,"悬赏红包");
                 //名额减一
                $HbAreaList->where('hb_id',$param['hb_id'])->setDec('remaining_quota', 1);
                    // 减掉对应的金额  更新剩下红包余额
                $HbAreaList->save(['remaining_hb_amount'=>($remainMoney-$money)],['hb_id'=>$param['hb_id']]);
                return '评论成功,本次获得红包'.$money.'元';
            }else{
                // 没名额了
                TApiException('该红包已被领取完了！',20011,200);
            }






        }



    }
    public function getHbDetailCommentList(){
        return $this->where('show','<>','0')->where('hb_id',request()->param('hb_id'))->with('User')->visible(['user'=>['user_pic']])->select();
    }
}
