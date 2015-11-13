<?php
namespace Home\Controller;
use Common\Controller\PublicController;

/**
 * 	用户空间
 * 	2014.8.26 @lizhe
 */
class SpaceController extends PublicController {


	/**
	 * SEO 设置相关 
	 * @var array
	 */
	private $_seoSetting = array();

	/**
	 * 用户状态
	 * 未登录的去登录 已登录的回首页
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

        //搜索用URL名称
        $this->assign('searchUrl', '/search');

        //指定LAYOUT
        if (!IS_AJAX){
            layout('Space/layout');
        }else{
            layout(false);
        }
	}


	/**
	 * 空操作 所有会员ID
	 * @return [type] [description]
	 */
	public function _empty($uid=0)
	{  
        
        //没有ID 取已登录ID
        if (!$uid) {
            $uid = $this->_Guser['uid'];
        }
        //该用户资料
        $membersDb = D('Member/Members');
        $membersArr = $membersDb->getProfile($uid);
        $this->assign('membersArr', $membersArr);

        //print_r($membersArr);

        //在没有ID返回错误 
        if (!$uid || !$membersArr) {
            $this->error('没有找到该用户, 请检查输入是否正确');
        }
        $this->assign('uid', $uid);

        //导航为首页
        $this->assign('onIndex', 1);



        $page = I('get.page', 1);
        $this->assign('page', $page);
        //用户评论内容
        $commentDb = D('Home/CmsComment');
        $commentArr = $commentDb->getCommentBySet($page,  U($this->_urlName, array('page'=>NULL)).'/page/', 0, $uid, 1, 10);
        $this->assign('commentArr', $commentArr);

        //标题
        $title = $membersArr['realname'].'的主页';
        $this->assign('title', $title);


        $this->_seoSetting['title'] = $title;
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display('space');
	}

    /**
     * 账号设置
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function setup()
    {
		

        //未登录
        $loginArr = $this->_Guser;
        if (!$loginArr){
            $this->redirect('/member/login', array('ref'=>getRef()));
        }
        $this->assign('uid', $loginArr['uid']);

        //TAB
        $app = I('get.app', 1);
        $this->assign('app', $app);

        //注册相关的控制
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoArr = $reginfoDb->get();
        $this->assign('reginfoArr', $reginfoArr);
        //echo $reginfoArr['realname_must'];
        //print_r($reginfoArr);

        //个人信息设置
        if ($app == 1) {
        	$title = '个人信息修改';

        	//用户当前 信息
        	$profileDb = D('Member/MembersProfile');
        	$profileArr = $profileDb->get($loginArr['uid']);
        	$this->assign('profileArr', $profileArr);
        }
        
        //修改头像
        if ($app == 2) {
        	$title = '修改头像';
			$myAvatar = $this->_Gset['IMG_AVATAR'].$loginArr['uid'].'/'.$loginArr['uid']."_big.jpg?".rand(10, 99);
			$this->assign('myAvatar', $myAvatar);

        }
		

		//账号资料
		if ($app == 3) {
			$title = '账号资料修改';
		}

        $this->assign('title', $title);

        //用户当前 账号资料
        $membersDb = D('Member/Members');
        $membersArr = $membersDb->getProfile($loginArr['uid']);
        $this->assign('membersArr', $membersArr);


		$this->_seoSetting['title'] = $title;
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }
}
