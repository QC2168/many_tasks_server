<?php

namespace app\common\validate;


use app\lib\exception\BaseException;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($scene = false)
    {
        //获取请求传递过来的参数
        $params = request()->param();
        //开始验证
        $check = $scene ? $this->scene($scene)->check($params) : $this->check($params);
        //开始验证
        if (!$check) {
            throw new BaseException(['msg' => $this->getError(), 'errorCode' => 10000, 'code' => 200]);
        }
        return true;
    }

    protected function regPhone($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\User::field('phone')->where('phone', $value)->find()) {
            return "该手机号已被注册过了";
        }
        return true;
    }

    protected function istenfold($value, $rule = '', $data = '', $field = '')
    {
        if (($value % 10) != 0) {
            return "金额有误";
        }
        return true;
    }

    protected function isDyTaskId($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\DyTaskList::field('dy_task_id')->where(['dy_task_id' => $value, 'show' => 1])->find()) {
            return true;
        }
        return "没有该DY任务";

    }

    protected function isDyTaskIdSelect($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\DyTaskList::field('dy_task_id')->where(['dy_task_id' => $value])->find()) {
            return true;
        }
        return "没有该DY任务";

    }

    protected function isDyTaskOrderSn($value, $rule = '', $data = '', $field = '')
    {
        if ($order = \app\common\model\DyTaskOrder::field('orderSn')->where(['orderSn' => $value])->find()) {
            return true;
        }
        return "您没有该DY任务订单";

    }

    protected function isTaskId($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\TaskList::field('task_id')->where(['task_id' => $value, 'show' => 1])->find()) {
            return true;
        }
        return "没有该XS任务";

    }

    protected function isTaskIdSelect($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\TaskList::field('task_id')->where(['task_id' => $value])->find()) {
            return true;
        }
        return "没有该XS任务";

    }
    protected function isRewardTaskId($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\RewardTaskList::field('reward_task_id')->where(['reward_task_id' => $value, 'show' => 1])->find()) {
            return true;
        }
        return "没有该FL任务";

    }

    protected function isRewardTaskIdSelect($value, $rule = '', $data = '', $field = '')
    {
        if (\app\common\model\RewardTaskList::field('reward_task_id')->where(['reward_task_id' => $value])->find()) {
            return true;
        }
        return "没有该FL任务";

    }
    protected function isTaskOrderSn($value, $rule = '', $data = '', $field = '')
    {
        if ( \app\common\model\TaskOrder::field('orderSn')->where(['orderSn' => $value])->find()) {
            return true;
        }
        return "您没有该XS任务订单";

    }
    protected function isRewardTaskOrderSn($value, $rule = '', $data = '', $field = '')
    {
        if ( \app\common\model\RewardTaskOrder::field('orderSn')->where(['orderSn' => $value])->find()) {
            return true;
        }
        return "您没有该Fl任务订单";

    }

    protected function isArr($value, $rule = '', $data = '', $field = '')
    {
        $arr = json_decode($value);
        if (is_array($arr)) {
            return true;
        };
        return "NOT ARRAY";
    }

    protected function typeName($value, $rule = '', $data = '', $field = '')
    {
        $arr =['push_task','push_dy_task','out','push_reward_task'];

        return (!in_array($value, $arr))?"非法参数":true;

    }

    protected function isCKey($value, $rule = '', $data = '', $field = '')
    {
        if ( \app\common\model\RechargeKey::field('key')->where(['key'=>$value,'status' => 1])->find()) {
            return true;
        }
        return "卡密已被使用/不存在";

    }
    protected function isRewardGoodsPlatformType($value, $rule = '', $data = '', $field = '')
    {
        $type = array("淘宝", "拼多多", "京东", "天猫","阿里巴巴","其他");
        if(in_array($value,$type)){
            return true;
        }
        return "平台类型出错";
    }
    protected function isRewardGoodsType($value, $rule = '', $data = '', $field = '')
    {
        $type = array("红包试用", "商品试用");
        if(in_array($value,$type)){
            return true;
        }
        return "平台类型出错";

    }

}
