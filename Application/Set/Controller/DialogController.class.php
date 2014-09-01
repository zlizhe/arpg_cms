<?php
namespace Set\Controller;
use Common\Controller\PublicController;

/**
 *  后台 首页
 */
class DialogController extends PublicController {

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
        $this->_getPurview(MODULE_NAME, CONTROLLER_NAME, ACTION_NAME, false);
    }


    /**
     * 编辑会员
     * @return [type] [description]
     */
    public function editUser()
    {

        $uid = I('get.uid');
        $this->assign('uid', $uid); 

        $ref = I('get.ref');
        $this->assign('ref', $ref);

        //MEMBER DB
        $membersDb = D("Member/Members");

        //会员详情
        $membersArr = $membersDb->getProfile($uid);
        $this->assign("membersArr", $membersArr);

        if (!$uid || !$membersArr) {
            $this->error('没有指定用户, 或指定用户不存在');
        }

        //角色组相关
        $groupDb = D("Member/MembersGroup");
        $groupArr = $groupDb->getGroupList();
        $this->assign('groupArr', $groupArr);


        $this->display('Set/Dialog/editUser');
    }


    /**
     * 添加新用户 后台快速添加
     */
    public function addUser()
    {

        $ref = I('get.ref');
        $this->assign('ref', $ref);

        //角色组相关
        $groupDb = D("Member/MembersGroup");
        $groupArr = $groupDb->getGroupList();
        $this->assign('groupArr', $groupArr);
        
        $this->display('Set/Dialog/addUser');
    }

    /**
     * 编辑用户组 权限
     * @return [type] [description]
     */
    public function editGroup()
    {
        $ref = I('get.ref');
        $this->assign('ref', $ref);

        $ac = I('get.ac', 'add');
        $this->assign('ac', $ac);

        //编辑角色
        if ($ac == 'edit') {

            //取当前组的情况
            $gid = I('get.gid');
            $groupDb = D('Member/MembersGroup');
            $groupArr = $groupDb->get($gid);
            $this->assign('groupArr', $groupArr);

            //print_r($groupArr);

            //ERROR
            if (!$groupArr || !$gid) {
                $this->error('用户组不存在');
            }
        }


        //权限列表
        $competenceDb = D('Member/MembersCompetence');
        $competenceArr = $competenceDb->getComList();
        $this->assign('competenceArr', $competenceArr);


        $this->display('Set/Dialog/editGroup');
    }


    /**
     * 编辑权限
     * @return [type] [description]
     */
    public function editCompetence()
    {
        $ref = I('get.ref');
        $this->assign('ref', $ref);

        $ac = I('get.ac', 'add');
        $this->assign('ac', $ac);

        //所有权限  供父级使用
        $competenceDb = D('Member/MembersCompetence');
        $competenceList = $competenceDb->getComList();
        $this->assign('competenceList', $competenceList);

        if ($ac == 'edit') {
            $cid = I('get.cid');
            //权限表
            $competenceArr = $competenceDb->get($cid);
            $this->assign('competenceArr', $competenceArr);
        }

        $this->display('Set/Dialog/editCompetence');
    }


    /**
     * 编辑  导航设置
     * @return [type] [description]
     */
    public function editNav()
    {
        $ref = I('get.ref');
        $this->assign('ref', $ref);
        //操作
        $ac = I('get.ac', 'add');
        $this->assign('ac', $ac);


        //NAV DB
        $navDb = D('Service/ServiceNav');

        if ($ac == 'edit') {
            $nid = I('get.nid');
            //导航详情
            $navArr = $navDb->get($nid);
            $this->assign('navArr', $navArr);
        }

        //导航列表
        $navList = $navDb->getNavOne();
        $this->assign('navList', $navList);

        $this->display('Set/Dialog/editNav');
    }

    /**
     *  添加新主题
     *  1. 普通用户模式，添加已下载的主题包
     *  2. 开发模式，创建模板主题默认文件包
     */
    public function addThemes()
    {
        $ref = I('get.ref');
        $this->assign('ref', $ref);


        $this->display('Set/Dialog/addThemes');
    }

    /**
     * 清理静态文件 缓存 
     * @return [type] [description]
     */
    public function rmCache()
    {
        $ref = I('get.ref');
        $this->assign('ref', $ref);

        //清理类型
        $type = I('get.type', 'all');
        $this->assign('type', $type);
        
        $this->display('Set/Dialog/rmCache');
    }


    /**
     * 添加新 分类 
     */
    public function editCategory()
    {
        $ref = I('get.ref');
        $this->assign('ref', $ref);

        $ac = I('get.ac', 'add');
        $this->assign('ac', $ac);

        //CMS 分类 DB
        $catDb = D('Home/CmsCategory');

        //所有分类
        $catList = $catDb->getCatList();
        $this->assign('catList', $catList);

        //编辑 时 输出编辑内容
        if ($ac == 'edit') {
            $cid = I('get.cid');
            //编辑内容 数组
            $catArr = $catDb->get($cid);
            $this->assign('catArr', $catArr);
        }

        
        $this->display('Set/Dialog/editCategory');
    }

    /**
     * 上传封面图片  文章
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function articleUpImg()
    {

        $imgpath = I('get.imgpath', '');
        //没有获取到图片信息
        if (!$imgpath) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('图片载入失败,请刷新后重试')</script>";
        }
        $this->assign('imgpath', $imgpath);

        //图片属性
        $imgSize = getimagesize(getcwd().$imgpath);
        $this->assign('imgSize', $imgSize);
        

        //随机数字 防止浏览器级别缓存 
        $num = rand(10, 99);
        $this->assign('num', $num);

        //ACTION 提交位置
        $this->assign('action', U('Set/Ajax/cutImg'));
        
        $this->display('Set/Dialog/articleUpImg');
    }
}