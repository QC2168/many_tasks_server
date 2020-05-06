<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use think\Request;
use app\common\model\News as NewsModel;

class News extends BaseController
{
   public function getNews(){
       $list = (new NewsModel())->getNews();
       return self::showResCode('获取成功',$list);
   }
}
