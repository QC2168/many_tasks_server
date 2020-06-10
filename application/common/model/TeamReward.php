<?php

namespace app\common\model;

use think\Model;

class TeamReward extends Model
{
    // 设置团队提现奖励
    public function setOutReward(){
        $outNumber=request()->param('number');
        $price=request()->param('price');
        switch ($outNumber){
            case '1':
                $this->save(['value'=>$price],['type'=>'sub_out_one']);
                break;
            case '2':
                $this->save(['value'=>$price],['type'=>'sub_out_two']);
                break;
            case '3':
                $this->save(['value'=>$price],['type'=>'sub_out_three']);
                break;
            case '4':
                $this->save(['value'=>$price],['type'=>'sub_out_four']);
                break;
            case '5':
                $this->save(['value'=>$price],['type'=>'sub_out_five']);
                break;
            default:
                TApiException('参数出错',20020,200);
        }
        return true;
    }
}
