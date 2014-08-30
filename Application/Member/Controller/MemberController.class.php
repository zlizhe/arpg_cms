<?php
namespace Member\Controller;
use Common\Controller\PublicController;

/**
 * 	MEMBER 模块
 * 	2014.7.14 @lizhe
 */
class MemberController extends PublicController {


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
        //已经登录过 了
        if ($this->_Guser){
            $this->redirect('/');
        }

        //标题
        $this->_seoSetting['siteName'] = $this->_Gset['SITE_NAME'];
	}


	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty()
	{  
        //去登录
		$this->redirect('/member/login');
	}

	/**
	 * 登录模块
	 * @return 登录页面
	 */
    public function login(){
        
		//显示 登录页面
		$user = I('get.user');
        $this->assign('user', $user);

        //跳转
        $ref = I('get.ref');
        $this->assign('ref', $ref);

    	$this->_seoSetting['title'] = '用户登录';
    	$this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }

    /**
     * 注册模块
     * @return 注册页面
     */
    public function register()
    {	
        

        //处理注册数据 
        if (IS_POST) {
            $registerArr['username']  = I('username');
            $registerArr['phone']     = I('phone');
            $registerArr['email']     = I('email');
            $registerArr['password']  = I('password');
            $registerArr['password2'] = I('password2');
            $registerArr['verify']    = I('verify');
            $registerArr['agreement'] = I('agreement', 0);

            //扩展输入项目
            $registerArr['realname']  = I('realname');
            $registerArr['gender']  = I('gender');
            $registerArr['age']  = I('age');


            //验证输入，
            $memberLogic = D('Member/Member', 'Logic');
            //print_r($member_logic);
            $error = $memberLogic->getRegCheck($registerArr);

            //print_r($error);
            //显示错误信息
            if ($error) {

                //print_r($reg_error);
                //显示错误 
                $this->assign('error', $error);
                //显示已输入的数据
                $this->assign('registerArr', $registerArr);
            }else{

                //普通会员
                $groupid = 1;
                //标准格式化
                $saveData = array(
                    'username'      => $registerArr['username'],
                    'email'         => $registerArr['email'],
                    'phone'         => $registerArr['phone'],
                    'password'      => md5($registerArr['password']),
                    'status'        => 1,
                    'regip'         => get_client_ip(),
                    'regdate'       => getTime(),
                    'groupid'       => $groupid,
                    //PROFILE DB 
                    'realname'      => $registerArr['realname'],
                    'gender'        => $registerArr['gender'],
                    'age'           => $registerArr['age'],
                );

                // print_r($saveData);
                // exit();

                //创建账号
                $onReg = $memberLogic->setRegUser($saveData);

                if($onReg){
                    //exit();
                    //SESSION UID
                    // 设置登录
                    $memberLogic->setLoginNow($onReg, 0);
                    //成功注册 
                    $this->success('注册成功, 正在跳转...', '/', 2);
                    exit();
                }
            }
            
        }

        //表单情况
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoArr = $reginfoDb->get();
        $this->assign('reginfoArr', $reginfoArr);

    	$this->_seoSetting['title'] = '立即注册';
    	$this->assign('seoSetting', $this->_seoSetting);
    	$this->display();
    }


    /**
     * 找回密码
     * @return [description]
     */
    public function forgot()
    {

        $step = intval(I('step', 1));

        //会员表
        $memberDb = D('Member/Members');

        
        //输入账号
        if ($step == 1) {
            $error = '';
            if (IS_POST) {
                $forgotArr['email'] = I('post.email');
                $forgotArr['verify'] = I('post.verify');
                //验证
                $memberLogic = D('Member/Member', 'Logic');
                $emailErr = $memberLogic->checkLoginAccount($forgotArr['email']);
                if ($emailErr != 1) {
                    $error['email'] = $emailErr;
                }
                if (checkVerify($forgotArr['verify']) != true) {
                    $error['verify'] = '验证字符不正确, 请重新输入';
                }
                //输出错误 
                if ($error) {
                    //显示错误 
                    $this->assign('error', $error);
                    //显示已输入的数据
                    $this->assign('forgotArr', $forgotArr);
                }else{
                    // echo 1;
                    // exit();
                    session('forgot', $forgotArr['email']);
                    $step++;
                }
            }

            //print_r($forgotArr);
        }


        //发送验证邮件
        if ($step == 2) {
            $forgotEmail = session('forgot');
            $this->assign('forgotEmail', $forgotEmail);

            //MAIL URL 处理
            $mailUrl = explode('@', $forgotEmail);
            $mailUrl = $mailUrl[1];
            $this->assign('mailUrl', $mailUrl);

            //发送验证邮件
            //SALT 发送时间 入库
            $forgot_date = date("YmdH", getTime());
            $forgot_salt = sha1(dechex(rand(99, 9999)));
            $data = array(
                'forgot_salt'   => $forgot_salt,
                'forgot_date'   => $forgot_date,
                'fotgot_status' => 1,
            );
            
            //账号信息
            
            $memberArr = $memberDb->getUserByAccount($forgotEmail);
            $this->assign('memberArr', $memberArr);

             //更新值
            $memberDb->setUpdate($data, $memberArr['uid']);

            $now = date("Y-m-d H:i:s", getTime());
            //发送验证邮件 邮件样本入库 @lizhe 2014.8.28
            $content = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;该邮件为".$this->_Gset['SITE_NAME']."(".$this->_Gset['SITE_URL'].") 会员密码找回专用邮件, 如果您没有申请密码找回服务, 请直接忽略本邮件, 如果您主动忽略本邮件我们不会修改您的密码及其他信息。
                <br/><br/><br/>
                尊敬的会员 ".$forgotEmail.", 您已在 [".$now."] 进行了密码找回操作, 请点击以下链接(如果无法点击请完整复制该链接到您的浏览器中打开):
                <br/><br/>
                ".$this->_Gset['SITE_URL']."/member/forgot/step/3/uid/$memberArr[uid]/salt/$forgot_salt 
                <br/><br/>
                点击链接进行您的密码重置操作！为了保证您的账户安全, 该链接仅一次有效, 请在2小时内完成操作！
                <br/><br/><br/>
                本邮件为系统自动发出请匆回复
                <br/>
                ".$this->_Gset['SITE_NAME']."会员中心<br/>
            ";            
            sendMail($forgotEmail, $this->_Gset['SITE_NAME'].' 找回密码专用邮件', $content, NULL, $this->_Gset['SITE_NAME'].'会员中心');
        }

        //重置密码
        if ($step == 3) {

            $uid = intval(I('get.uid'));
            $salt = trim(I('get.salt'));

            if ($uid && $salt) {
                $uRes = $memberDb->get($uid);
                //有效时间
                $isDate = date("YmdH", getTime()) - $uRes['forgot_date'];

                if ($uRes['fotgot_status'] != 1){
                    $this->error('该链接仅一次有效并已被执行操作, 您不能重复执行该操作');
                }
                if ($isDate > 2){
                    $this->error('您超过2小时没有执行重置新密码操作, 系统已退回该申请!');
                }
                if ($uRes['forgot_salt'] != $salt){
                    $this->error('来源地址不正确, 请检查您的地址是否完整！');
                }
                //写入已使用记录
                $data = array(
                    'fotgot_status'   => 0,
                );
                $memberDb->setUpdate($data, $uid);

                //写入SESSION 允许设置新密码
                session('forgotAccount', $uid);
            }else{
                //地址不完整
                $this->error('来源地址不正确, 请检查您的地址是否完整！');
            }
        }

        $this->assign('step', $step);

        $this->_seoSetting['title'] = '找回密码';
        $this->assign('seoSetting', $this->_seoSetting);
    	$this->display();
    }
}