<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\TeamRewardValidate;
use think\Controller;
use think\Request;
use app\common\model\TeamReward as TeamRewardModel;

class TeamReward extends BaseController
{
    public function setOutReward(){
        (new TeamRewardValidate())->goCheck('setOutReward');
        (new TeamRewardModel())->setOutReward();
        return self::showResCodeWithOutData('设置成功');
    }
}
