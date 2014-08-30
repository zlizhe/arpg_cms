<?php
namespace Set\Controller;
use Common\Controller\PublicController;

/**
 *  后台 首页
 */
class SetController extends PublicController {

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

        //是否有权限 [后台专用]
        $this->_getPurview(MODULE_NAME, CONTROLLER_NAME, ACTION_NAME);

        //未登录
        if (!$this->_Guser){
            $this->redirect('/member/login', array('ref'=>getRef()));
        }

        //当前操作ACTION
        $this->assign('action', ACTION_NAME);

        //标题
        $this->_seoSetting['siteName'] = $this->_Gset['SITE_NAME'];

        //LAYOUT  指定
        layout('Set/layout');
        $this->theme('default');
    }

    /**
     * 管理后台首页 信息
     * @return [type] [description]
     */
    public function index(){

        //转向管理中心 第一个默认耍耍
        $this->redirect('/set/setting');

        $this->_seoSetting['title'] = '管理中心';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 会员管理
     * @return [type] [description]
     */
    public function user()
    {

        //后台分组
        $this->assign('setPart', 1);

        $app = I('get.app', 1);
        $this->assign('app', $app);

        
        $page = I("get.page", 1);
        $this->assign('page', $page);
        $query = I('get.query', '');
        $this->assign('query', $query);

        $group = I('get.group', 0);
        $this->assign('group', $group);
        //所有角色
        $groupDb = D('Member/MembersGroup');
        $groupArr = $groupDb->getGroupList();
        $this->assign('groupArr', $groupArr);

        // 取所有会员 
        $memberDb = D("Member/Members");
        $membersArr = $memberDb->getMemberList($page, U($this->_urlName, array('app'=>$app, 'group'=>$group, 'query'=>$query, 'page'=>NULL)).'/page/', $app, $group, $query);
        $this->assign('membersArr', $membersArr);

        //print_r($u);
        //print_r($membersArr);

        $this->_seoSetting['title'] = '会员管理';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 用户组 角色管理
     * @return [type] [description]
     */
    public function group()
    {

        //后台分组
        $this->assign('setPart', 1);

        //GROUP LIST ..
        $groupDb = D('Member/MembersGroup');
        $groupArr = $groupDb->getGroupList();
        $this->assign('groupArr', $groupArr);

        //print_r($groupArr);

        $this->_seoSetting['title'] = '角色管理';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 权限设置
     * @return [type] [description]
     */
    public function competence()
    {   

        //后台分组
        $this->assign('setPart', 1);

        //competence list ... 
        $competenceDb = D('Member/MembersCompetence');
        $competenceArr = $competenceDb->getComList();
        $this->assign('competenceArr', $competenceArr);

        $this->_seoSetting['title'] = '权限设置';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 导航设置
     * @return [type] [description]
     */
    public function nav()
    {
        //后台分组
        $this->assign('setPart', 2);

        //NAV DB
        $navDb = D('Service/ServiceNav');

        //导航列表
        $navArr = $navDb->getNavList();
        $this->assign('navArr', $navArr);

        //配置
        //echo $this->_Gset['site_home']
        $this->assign('site_home', $this->_Gset['SITE_HOME']);

        $this->_seoSetting['title'] = '导航设置';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 主题管理
     * @return [type] [description]
     */
    public function themes()
    {
        //后台分组
        $this->assign('setPart', 2);

        //themes db
        $themesDb = D('Service/ServiceThemes');

        //主题数组
        $themesArr = $themesDb->getThemesList();
        $this->assign('themesArr', $themesArr);

        //默认主题
        $this->assign('site_themes', $this->_Gset['SITE_THEMES']);

        //print_r($settingArr);


        $this->_seoSetting['title'] = '主题管理';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 网站功能设置
     * @return [type] [description]
     */
    public function setting()
    {
        //后台分组
        $this->assign('setPart', 1);

        //SETTING DB...
        $settingDb = D('Service/ServiceSetting');

        //配置数据  PUBLIC CONTROLLER已经取得了 是否还有必要两次取数据
        $settingArr = $settingDb->get();
        $this->assign('settingArr', $settingArr);

        $this->_seoSetting['title'] = '网站设置';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 访问统计
     * @return [type] [description]
     */
    public function analytics()
    {
        //后台分组
        $this->assign('setPart', 1);

        $this->_seoSetting['title'] = '访问统计';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }


    /**
     * 注册控制 
     * @return [type] [description]
     */
    public function reginfo()
    {
        //后台分组
        $this->assign('setPart', 1);

        //注册控制表
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoArr = $reginfoDb->get();
        $this->assign('reginfoArr', $reginfoArr);

        $this->_seoSetting['title'] = '注册控制';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }


    /**
     * 分类管理
     * @return [type] [description]
     */
    public function category()
    {
        //后台分组
        $this->assign('setPart', 3);

        //CAT DB 
        $catDb = D('Home/CmsCategory');

        //列表
        $catArr = $catDb->getCatList();
        $this->assign('catArr', $catArr);

        //网站地址
        $this->assign('site_url', $this->_Gset['SITE_URL']);

        $this->_seoSetting['title'] = '分类管理';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 内容管理 
     * @return [type] [description]
     */
    public function article()
    {
        //后台分组
        $this->assign('setPart', 3);


        $app = I('get.app', 1);
        $this->assign('app', $app);

        //所有参数
        $page = I("get.page", 1);
        $this->assign('page', $page);
        $query = I('get.query', '');
        $this->assign('query', $query);

        //通过分类
        $cat_id = intval(I('get.cat_id'));
        $this->assign('cat_id', $cat_id);

        //是否有超级管理权限 
        if (isPurview('Common_article', false, false)) {
            //通过用户 :: 查看 无管理权限的 无法查找 默认为 0 显示 所有
            $created_uid = intval(I('get.created_uid'));
            $this->assign('created_uid', $created_uid);
        }else{
            $created_uid = $this->_Guser['uid'];
            //echo $created_uid;
        }


        //article db ...
        $articleDb = D('Home/CmsArticle');
        //文章列表
        $articleArr = $articleDb->getArticleList($page, U($this->_urlName, array('app'=>$app, 'query'=>$query, 'page'=>NULL)).'/page/', $cat_id, $created_uid, $app, $query);
        $this->assign('articleArr', $articleArr);

        $this->_seoSetting['title'] = '文章管理';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 新增 编辑  内容
     * @return [type] [description]
     */
    public function editArticle()
    {
        //后台分组
        $this->assign('setPart', 3);

        //新增OR编辑 
        $ac = I('get.ac', 'add');
        $this->assign('ac', $ac);

        //分类DB...
        $catDb = D('Home/CmsCategory');

        //所有分类 
        $catList = $catDb->getCatList();
        $this->assign('catList', $catList);

        $contentLogic = D('Home/Content', 'Logic');
        //没有分类 必须先创建一个分类
        $contentLogic->isCategory();

        //添加新内容
        if ($ac == 'add') {
            $title = '添加新文章';
        }


        //编辑 内容
        if ($ac == 'edit') {
            $aid = intval(I('get.aid'));

            $articleDb = D('Home/CmsArticle');
            $articleArr = $articleDb->get($aid);
            //无图无真相
            if (!$aid || !$articleArr) {
                $this->error('没有找到该文章');
            }
            //标题内容
            $title = '编辑 "'.$articleArr['title'].'"';

            //tags to array 
            if ($articleArr['tags']) {
                $tags = explode(',', $articleArr['tags']);
                $this->assign('tags', $tags);
            }
            //print_r($articleArr);
            $this->assign('articleArr', $articleArr);
        }

        //输出title
        $this->assign('title', $title);
        
        $this->_seoSetting['title'] = $title;
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 评论管理
     * @return [type] [description]
     */
    public function comment()
    {

        //后台分组
        $this->assign('setPart', 3);

        $app = I('get.app', 1);
        $this->assign('app', $app);

        //可选参数
        $page = I("get.page", 1);
        $this->assign('page', $page);
        $aid = I('get.aid', 0);
        $this->assign('aid', $aid);
        $created_uid = I('get.created_uid', 0);
        $this->assign('created_uid', $created_uid);

        //评论表
        $commentDb = D('Home/CmsComment');
        $commentArr = $commentDb->getCommentBySet($page, U($this->_urlName, array('app'=>$app, 'aid'=>$aid, 'created_uid'=>$created_uid, 'page'=>NULL)).'/page/', $aid, $created_uid, $app);
        $this->assign('commentArr', $commentArr);


        $this->_seoSetting['title'] = '评论管理';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }


    /**
     * 首页滚动图片设置
     * @return [type] [description]
     */
    public function slide()
    {
        //后台分组
        $this->assign('setPart', 2);

        $slideDb = D('Service/ServiceSlide');
        //所有滚动图片
        $slideArr = $slideDb->getSlideList();
        $this->assign('slideArr', $slideArr);

        $this->_seoSetting['title'] = '首页滚动图片';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }


    /**
     * OSS 设置
     * @return [type] [description]
     */
    public function oss()
    {
        //后台分组
        $this->assign('setPart', 4);

        //oss 设置
        $ossDb = D('Service/ServiceOss');
        $ossArr = $ossDb->get();
        $this->assign('ossArr', $ossArr);

        
        $this->_seoSetting['title'] = 'OSS上传设置';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }
}