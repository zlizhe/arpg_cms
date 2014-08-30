<?php
namespace Dialog\Controller;
use Common\Controller\PublicController;

/**
 *  DIALOG 所有弹出窗口
 */
class DialogController extends PublicController {

    /**
     * 弹出容器的 TITLE
     * @var array
     */
    private $_title = array();

    /**
     * 前置操作
     * @return [type] [description]
     */
    public function _initialize()
    {
        //执行开始
        $this->_run();
    }

    /**
     * 操作成功
     * @return [type] [description]
     */
    public function success(){

        //文字
        $res = I('get.res');
        $this->assign('res', $res);

        $siteUrl = $this->_Gset['SITE_URL'];
        //链接
        $url = $siteUrl.urldecode(I('get.url', '/'));
        $this->assign('url', $url);


        $this->display();
    }

    /**
     * 账号登出
     * @return [type] [description]
     */
    public function logout()
    {
        $this->assign('res', '您已安全登出账号, 正在跳转...');

        //登出
        $memberLogic = D('Member/Member', 'Logic'); 
        $memberLogic->unsetLogin();

        //网站 URL 跳转
        $siteUrl = $this->_Gset['SITE_URL'];
        //链接
        $url = $siteUrl.urldecode(I('get.ref', '/'));
        $this->assign('url', $url);


        $this->display('success');
    }

    /**
     * uploading 正在上传 动画
     * @return [type] [description]
     */
    public function uploading()
    {
        $this->display();
    }

    /**
     * 快速登录弹出 
     * @return [type] [description]
     */
    public function login()
    {
        //网站 URL 跳转
        $siteUrl = $this->_Gset['SITE_URL'];

        $url = I('get.ref', '/');
        $this->assign('url', $url);

        $this->display();
    }

    /**
     * 上传头像图片
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function avatarUpImg()
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
        $this->assign('action', U('Member/Ajax/cutImg'));
        
        $this->display('set/dialog/articleUpImg');
    }


}