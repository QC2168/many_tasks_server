<?php

namespace app\common\model;

use think\Model;

class OutId extends Model
{
    // 认证
    public function userAuth(){
        $zfb=request()->param('zfb');
        $name=request()->param('name');
        // 查询是否认证过
        $userAuthData=$this->where('username',request()->username)->find();
        if (!empty($userAuthData))TApiException('已经认证过了',20013,200);
        // 插入认证数据
        $this->save(['zfb'=>$zfb,'name'=>$name,'username'=>request()->username]);
        return true;
    }

    public function checkAuth(){
        $userAuthData=$this->where('username',request()->username)->find();
        if (empty($userAuthData)){
            // 空数据 没有认证
            return false;
        }else{
            return true;
        }
    }
}
