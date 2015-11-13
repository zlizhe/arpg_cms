<?php
namespace Home\Controller;
use Common\Controller\PublicController;

/**
 *  CMS 首页
 */
class IndexController extends PublicController {

    /**
     * SEO 设置相关 
     * @var array
     */
    private $_seoSetting = array();

    /**
     * 前置操作
     * @return [type] [description]
     */
    public function _initialize()
    {
        //执行开始
        $this->_run();
        //标题
        $this->_seoSetting['siteName'] = $this->_Gset['SITE_NAME'];

        // 导航相关
        $navDb = D('Service/ServiceNav');
        $navArr = $navDb->getNavZero(__INFO__);
        $this->assign('navArr', $navArr);


        //print_r($this->ref);
        //print_r($this->_Guser);
        //指定LAYOUT
        if (!IS_AJAX){
            layout('Index/layout');
        }else{
            layout(false);
        }
    }

    /**
     * ARPG CMS 首页
     * @return [type] [description]
     */
    public function index(){

        //引导为 网站首页
        $navDb = D('Service/ServiceNav');
        $navArr = $navDb->get($this->_Gset['SITE_HOME']);

        //$navLink = $navArr['link'];
        ////能判断是当前 CONTROLLER的URL
        //$thisIndex = array('/', './', '/home', $this->_Gset['SITE_URL'], $this->_Gset['SITE_URL'].'/');
        //
        //##### 跳转有小问题 当设置为 非INDEX页面为首页时 首页无法打开了 @lizhe 2014.8.26 #####
        ////如果设置首页 不是现在的首页 跳转...
        //if (!in_array($navLink, $thisIndex)) {
        //    $this->redirect($navArr['link']);
        //}
        
        //echo 1;
        //搜索用URL名称
        $this->assign('searchUrl', '/search');


        //所有分类
        $categoryDb = D('Home/CmsCategory');
        $categoryArr = $categoryDb->getByUseful();
        $this->assign('categoryArr', $categoryArr);


        $slideDb = D('Service/ServiceSlide');
        //所有滚动图片
        $slideArr = $slideDb->getSlideList();
        $this->assign('slideArr', $slideArr);


        $this->_seoSetting['title'] = '首页';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 文章浏览
     * @return [type] [description]
     */
    public function article($id)
    {
        //文章DB
        $articleDb = D('Home/CmsArticle');
        $articleArr = $articleDb->get($id);
        //无此文章
        if (!$articleArr) {
            $this->error('没有找到该文章, 请检查输入是否正确');
        }

        //已删除
        if ($articleArr['app'] == 2) {
            $this->error('该文章已被删除, 请返回');
        }
        //审核
        if ($articleArr['app'] == 3) {
            $this->error('该文章已被系统审核, 请返回');
        }

        //$articleArr['content'] = htmlspecialchars_decode($articleArr['content']);
        $this->assign('articleArr', $articleArr);

        //print_r($articleArr);
        //echo $articleArr['tags'];
        //TAGS
        $tagsArr = array_filter(explode(',', $articleArr['tags']));
        $this->assign('tagsArr', $tagsArr);

        //echo $articleArr['created_uid'];
        //作者情况
        $profileDb = D('Member/MembersProfile');
        $profileArr = $profileDb->get($articleArr['created_uid']);
        $this->assign('profileArr', $profileArr);

        //分类情况
        $categoryDb = D('Home/CmsCategory');
        $categoryArr = $categoryDb->get($articleArr['cat_id']);
        $this->assign('categoryArr', $categoryArr);

        //前一篇 后一篇
        $beforeId = $id - 1;
        $afterId = $id + 1;

        //echo $afterId;
        //前一篇
        $beforeArr = $articleDb->getByUseful($beforeId);
        $this->assign('beforeArr', $beforeArr[0]);
        //后一篇
        $afterArr = $articleDb->getByUseful($afterId);
        $this->assign('afterArr', $afterArr[0]);

        //print_r($afterArr);

        //搜索用URL名称
        $this->assign('searchUrl', '/search');

        //边栏文章
        $sameCatArr = $articleDb->getListByCat($categoryArr['id']);
        $this->assign('sameCatArr', $sameCatArr);

        //echo $id;
        $this->_seoSetting['title'] = $articleArr['title'];
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 分类浏览 / 列表页面
     * @param  [type] $catname [description]
     * @return [type]          [description]
     */
    public function category($path='search')
    {
        //echo $path;
        //分类情况
        $categoryDb = D('Home/CmsCategory');
        $categoryArr = $categoryDb->getByPath($path);
        $categoryArr = $categoryArr[0];

        //PATH 为SEARCH 展示不限分类 主要用于搜索
        if ($path == 'search') {
            $categoryArr = array(
                'id'    => 0,
                'value' => '全局搜索',
            );
            $urlName = '/search';
        }else{
            if (!$categoryArr) {
                $this->error('没有找到该分类, 请检查输入是否正确');
            }

            //未启用
            if ($categoryArr['app'] == 0) {
                $this->error('管理员已关闭该分类, 请稍后查看');
            }
            //print_r($categoryArr);
            //当前特殊URL NAME
            $urlName = '/'.$path;
            
        }

        $this->assign('categoryArr', $categoryArr);
        $this->assign('urlName', $urlName);


        $page = I("get.page", 1);
        $this->assign('page', $page);
        $query = I('get.query', '');
        $this->assign('query', $query);



        //该分类下的文章
        $articleDb = D('Home/CmsArticle');
        $articleArr = $articleDb->getArticleList($page, U($urlName, array('query'=>$query, 'page'=>NULL)).'/page/', $categoryArr['id'], 0, 1, $query, 20);
        $this->assign('articleArr', $articleArr);


        //边栏文章
        $sameCatArr = $articleDb->getListByCat($categoryArr['id']);
        $this->assign('sameCatArr', $sameCatArr);

        //搜索用URL名称
        $this->assign('searchUrl', $this->urlName);
        //print_r($articleArr);

        $this->_seoSetting['title'] = $categoryArr['value'];
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }
}