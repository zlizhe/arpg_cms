<?php
namespace Common\Controller;
use Think\Controller;

/**
 * 	全局控制器
 * 	用于输出全局函数 系统配置等
 * 	所有 CONTROLLER 预先加载该 控制器
 */
class PublicController extends Controller {

	/**
	 * 当前版本号
	 * @var string
	 */
	protected $_Gver = 'v0.3';

	/**
	 * 网站设置
	 * 网站自身的设置相关 与配置等
	 * @var array
	 */
	protected $_Gset = array();

	/**
	 * 用户相关
	 * 用户自己登录的数组 为登录为 NULL
	 * @var array
	 */
	protected $_Guser = array();

	/**
	 * 当前 URL 的域名
	 * 供U方法输出
	 * @var string
	 */
	protected $_urlName = '';

	/**
	 * 预先加载配置 $this->_run(); 
	 * @return [type] [description]
	 */
	protected function _run()
	{
		#####

		//会员相关
		$memberLogic = D('Member/Member', 'Logic');
		//登录数组
		$this->_Guser = $memberLogic->getIsLogin();

		$this->assign('_Guser', $this->_Guser);


        //设置相关
		$this->_Gset = $this->_getSiteSet();
		$this->assign('_Gset', $this->_Gset);

        //使用模板
        $this->theme($this->_Gset['SITE_THEMES']); //不替换默认模板 可以共用文件 类似DISCUZ机制 @lizhe 2014.7.17
        //自定义主题路径 
        $this->assign('_themeUrl', '/Template/'.$this->_Gset['SITE_THEMES']);
        //C('DEFAULT_THEME', $this->_Gset['SITE_THEMES']);
        
        //REF 全部指定
        $this->assign('ref', getRef());

        //URL NAME
        $this->_urlName = '/'.CONTROLLER_NAME.'/'.ACTION_NAME;
        $this->assign('urlName', $this->_urlName);

        //是否关闭网站
        $siteopen = $this->_Gset['SITEOPEN'];
        if ($siteopen == 0) {
        	//已登录的
        	if ($this->_Guser) {
	        	//是否有超级访问权限
	        	if (!isPurview('Common_view', false, false)) {
	        		$this->error($this->_Gset['SITE_NAME'].' 正在维护中, 喝杯咖啡稍候在来吧！');
	        	}

        	}else{
        		$this->error($this->_Gset['SITE_NAME'].' 正在维护中, 喝杯咖啡稍候在来吧！');
        	}
        }

        //已登录之后 
        if ($this->_Guser) {
            //是否有管理员权限 
            $isAdmin = isPurview('Set_Set_index', false, false);
            $this->assign('isAdmin', $isAdmin);
        }

        //当前 版本
        $this->assign('_Gver', $this->_Gver);
	}


	/**
	 * 取网站配置
	 * @return [type] [description]
	 */
	public function _getSiteSet()
	{
		//先读缓存
		$siteSetting = F('_Gset');

		if (!$siteSetting) {
			//配置DB
			$settingDb = D('Service/ServiceSetting');
			//配置数组
			$settingArr = $settingDb->get();
			//全局设置 数据库读取信息
			$siteSetting = array(
				'SITE_NAME'      => $settingArr['site_name'],  //网站名称
				'SITE_URL'       => $settingArr['site_url'], //网站域名
				'MAIL_SERVER'    => $settingArr['mail_server'], //邮件服务器
				'MAIL_USERNAME'  => $settingArr['mail_username'], //邮件发件人
				'MAIL_PASSWORD'  => $settingArr['mail_password'], //邮件密码
				'IMG_URL'        => $settingArr['img_url'], //图片域名 或 路径
				'IMG_AVATAR'     => $settingArr['img_avatar'], //头像域名 
				'SITE_THEMES'    => $settingArr['site_themes'], //使用模板
				'SITE_HOME'		 => $settingArr['site_home'], //默认首页
				'SITEOPEN'		 =>	$settingArr['siteopen'], //是否开启网站
				'ANALYTICS'		 =>	$settingArr['analytics'], //是否开启网站
			);
			//写入缓存 
			F('_Gset', $siteSetting);
		}
		//print_r($siteSetting);

		return $siteSetting;
	}

	/**
	 * 普通页面是否有 权限浏览
	 * @param  [type] $action [description]
	 * @return [type]         [description]
	 */
	public function _getPurview($module, $controller, $action, $return=true)
	{
		$actionName = $module.'_'.$controller.'_'.$action;
		// echo $actionName;
		// exit();
		// echo $return;
		// exit();
		return isPurview($actionName, $return);
	}
}