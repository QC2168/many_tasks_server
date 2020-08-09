<?php

namespace app\common\model;

use think\Model;

class HbAmount extends Model
{
public function getAmountList(){
    return $this->where('status',1)->select();
}
}
