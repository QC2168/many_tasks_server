<?php

namespace app\common\model;

use think\Model;

class HomePagePic extends Model
{
    public function getHomePic(){
        return $this->select();
    }
}
