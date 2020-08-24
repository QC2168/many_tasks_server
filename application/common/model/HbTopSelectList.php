<?php

namespace app\common\model;

use think\Model;

class HbTopSelectList extends Model
{
    public function getHbTopList(){
        return $this->where('status',1)->select();
    }
}
