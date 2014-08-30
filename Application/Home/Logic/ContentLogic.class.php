<?php
namespace Home\Logic;

/**
 * 	内容相关的逻辑操作
 */
class ContentLogic{


    /**
     * 必须先有一个分类 才能发布内容
     * @return boolean [description]
     */
    public function isCategory()
    {
        $catDb = D('Home/CmsCategory');
        //所有分类 
        $catList = $catDb->getCatList();

        //没有
        if (!$catList) {
            $catUrl = U('/set/category');
            //E("请先添加一个分类, <a href=\"".$catUrl."\">点击这里添加分类</a>");
            echo "<script>alert('请先添加一个分类!');</script>";
            echo "<script>window.open(\"$catUrl\", '_self');</script>";
            return false;
        }else{
            return true;
        }

    }

    /**
     * 各种+1 处理
     * @return [type] [description]
     */
    public function plusOne($aid, $type='view_num')
    {

        $is_view = '';
        //如果 是浏览数 查看是否有COOKI 没有COOKI则+1
        if ($type == 'view_num') {
            $is_view = cookie($type.'_'.$aid);
            //set cookie 
            cookie($type.'_'.$aid, '1', time() + 900);
        }

        //开始 +1
        if (!$is_view) {
            $articleDb = D('Home/CmsArticle');
            @$articleDb->setPlus($aid, $type);
        }

        return true;
    }

}