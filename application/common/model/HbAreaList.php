<?php

namespace app\common\model;

use think\Model;

class HbAreaList extends Model
{
    protected $autoWriteTimestamp = true;
    public function User()
    {
        return $this->hasOne('User','username','username');
    }
    public function HbAreaCommentList()
    {
        return $this->hasMany('HbAreaCommentList','hb_id','hb_id');
    }
    public function HbAreaPic()
    {
        return $this->hasMany('HbAreaPic','hb_id','hb_id');
    }
public function getHbAreaList(){

    $index = request()->param("index");
    $list=$this->where('show','<>','0')->with(['User','HbAreaPic','HbAreaCommentList'])->visible(['user'=>['user_pic'],'hb_area_comment_list'=>['content']])->page($index)->select();
    return $list;

}
public function getHbDetail(){
    return $this->where('show','<>','0')->where('hb_id',request()->param('hb_id'))->with(['HbAreaPic','HbAreaCommentList','User'])->visible(['user'=>['user_pic'],'hb_area_comment_list'=>['username','content','create_time']])->find();
}
    public function pushHb(){
        $param = request()->param();
        // 获取图片数据
        $hb_pic_list=json_decode($param['hb_pic_list'],true);
        // 判断余额是否足够
        $push_hb_amount = $param['hb_amount'];
        // 获取用户余额
        $user_wallet = (Assets::where('username', request()->username)->value('wallet'));
        if ($user_wallet >= $push_hb_amount) {
            // 余额充足
            //扣除余额
            Assets::where('username', request()->username)->update(['wallet' => ($user_wallet - $push_hb_amount)]);
            add_wallet_details(2,$push_hb_amount,"发送:".substr($param['content'], 0,3).'...');
            //创建任务
            $hb=$this->create([
                'username' => request()->username,
                'content' => $param['content'],
                'hb_amount' => $param['hb_amount'],
                'remaining_hb_amount' => $param['hb_amount'],
                'tag' => '正在派送红包...',
                'quota' => $param['quota'],
                'remaining_quota' => $param['quota'],
                'show' =>1,
            ]);
            $hb_id=$hb->id;
            //上传图片
            $hb_area_pic=new HbAreaPic();
            // 判断有没有图片列表
            if(empty($hb_pic_list)) return true;
            // 创建图片
            foreach ($hb_pic_list as $key => $value){
                $item =$hb_pic_list[$key];
                $hb_area_pic->create([
                    'hb_id'=>$hb_id,
                    'pic'=>$item
                ]);
            }
            return true;
        }else{
            // 余额不足
            TApiException('当前账户余额不足', 20005, 200);
        }
    }


}
