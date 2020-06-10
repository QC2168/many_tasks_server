<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\NewsValidate;
use think\Controller;
use think\Request;
use app\common\model\News as NewsModel;

class News extends BaseController
{
    // 获取消息
   public function getNews(){
       $list = (new NewsModel())->getNews();
       return self::showResCode('获取成功',$list);
   }
   // 发布消息
   public function postNews(){
       (new NewsValidate())->goCheck('postNews');
       $list = (new NewsModel())->postNews();
       return self::showResCodeWithOutData('发布成功');
   }
}
