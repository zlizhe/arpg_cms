<?php
namespace Member\Logic;

/**
 * 	用户 相关逻辑操作
 */
class MemberLogic{

    /**
     * 注册新用户
     * @param [type] $data        用户表相关数据
     * @param [type] $profileData 用户profile 相关
     */
    public function setRegUser($data)
    {
        //MEMBERS 数据
        $regData = array(
            'username' => $data['username'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => $data['password'],
            'status'   => $data['status'],
            'regip'    => $data['regip'],
            'regdate'  => $data['regdate'],
            'groupid'  => $data['groupid'],
        );
        
        //写入数据 
        $membersDb = D('Member/Members');
        $insertId = $membersDb->set($regData);


        //未设置REALNAME 使用账号
        if (!$data['realname']) {
            if ($data['username']) {
                $data['realname'] = $data['username'];
            }else{
                if ($data['email']) {
                    $data['realname'] = $data['email'];
                }else{
                    $data['realname'] = $data['phone'];
                }
            }
        }

        //PROFILE DATA ...
        $profileData = array(
            'uid'       => $insertId,
            'realname'  => $data['realname'],
            'gender'    => $data['gender'],
            'age'       => $data['age'],
        );

        
        //PROFILE 表写入初始
        $profileDb = D('Member/MembersProfile');
        $profileDb->set($profileData);
        // var_dump($profileData);
        // exit();

        // print_r($profileData);
        // echo "<script>parent.alert('".$id."')</script>";
        // exit();

        //#### 发送欢迎邮件
        // 2014.8.28 @lizhe

        //创建初始头像
        $avadir = getcwd().'/uploads/avatar/'.$insertId;
        creatDir($avadir);

        copy('./img/defaultavatar_big.jpg', $avadir."/".$insertId."_big.jpg");
        copy('./img/defaultavatar_small.jpg', $avadir."/".$insertId."_small.jpg");

        // //OSS 上传默认头像
        // $oss_path = $insertId;
        // $getbigfile = "/".$insertId."_big.jpg";
        // $getsmallfile = "/".$insertId."_small.jpg";
        // //OSS 上传
        // $oss_sdk_service = new ALIOSS();
        // //设置是否打开curl调试模式
        // $oss_sdk_service->set_debug_mode(FALSE);
        // //BUCKET NAME
        // $bucket = "ripppple-avatar";
        // $oss_sdk_service->create_object_dir($bucket, $oss_path);
        // //SMALL
        // $response = $oss_sdk_service->upload_file_by_file($bucket, $oss_path.$getbigfile, $avadir."/".$id."_big.jpg");
        // $response = $oss_sdk_service->upload_file_by_file($bucket, $oss_path.$getsmallfile, $avadir."/".$id."_small.jpg");


        return $insertId;
    }

    /**
     * 设置为登录成功 并设置COOKIE
     * @param [type] $uid       [description]
     * @param [type] $autologin [description]
     */
    public function setLoginNow($uid, $autologin=0)
    {
        //需要设置COOKIE
        if ($autologin) {
            $memberDb = D("Member/Members");
            $membersArr = $memberDb->get($uid);
            $data = array(
                'uid'      => $uid,
                'email'    => $membersArr['email'],
                'password' => $membersArr['password']
            );
            //unserialize 1个月 30天
            cookie("_member", serialize($data), 60 * 60 * 24 * 30);
        }

        //登陆成功后 设置登陆日志
        $loginlogDb = D("Member/MembersLoginlog");
        //LASTLOGIN
        $loginlogDb->set($uid);
        
        //设置SESSION
        session('uid', $uid);
        return true;
    }

    /**
     * 登录表单检查
     * @param $data [description]
     */
    public function getLoginCheck($data)
    {
        $results = "";
        $loginNum = cookie('_loginNum_'.$data['email']);
        //账号
        $checkAcc = $this->checkLoginAccount($data['email']);
        if ($checkAcc != 1){
            $results['email'] = $checkAcc;
        }
        //密码
        $checkPw = $this->_checkLoginPw($data['email'], md5($data['password']));
        if ($checkPw != 1){
            $results['password'] = $checkPw;
        }

        // 登录3次以上 检查 验证字符
        if ($loginNum >= 3){
            if (checkVerify($data['verify']) != true){
                $results['verify'] = '验证字符不正确, 请重新输入';
                echo "<script type=\"text/javascript\">parent.showCaptcha(1);</script>";
            }
        }

        if ($results) {
            echo "<script type=\"text/javascript\">parent.getCaptcha();</script>";
        }


        return $results;
    }

    /**
     * 注册表单检查
     * @param $data [description]
     */
    public function getRegCheck($data)
    {
        //注册控制表
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoArr = $reginfoDb->get();

        $results = "";
        //所有规则
        if ($data['agreement'] == 0){
            $results['agreement'] = "您必须同意才能继续";
        }

        //注册时展示 
        if ($reginfoArr['email_reg']) {
            //EMAIL
            $checkEmail = $this->_checkEmail($data['email'], $reginfoArr['email_must']);
            if ($checkEmail != 1){
                $results['email'] = $checkEmail;
            }
        }

        //注册时展示 
        if ($reginfoArr['username_reg']) {
            //USERNAME
            $checkUsername = $this->_checkUsername($data['username'], $reginfoArr['username_must']);
            if ($checkUsername != 1){
                $results['username'] = $checkUsername;
            }
        }

        //注册时展示 
        if ($reginfoArr['phone_reg']) {
            //PHONE
            $checkPhone = $this->_checkPhone($data['phone'], $reginfoArr['phone_must']);
            if ($checkPhone != 1){
                $results['phone'] = $checkPhone;
            }
        }

        //注册时展示 
        if ($reginfoArr['realname_reg']) {
            //REALNAME
            $checkRealname = $this->_checkRealname($data['realname'], $reginfoArr['realname_must']);
            if ($checkRealname != 1){
                $results['realname'] = $checkRealname;
            }
        }

        //注册时展示 
        if ($reginfoArr['gender_reg']) {
            //性别 
            $checkGender = $this->_checkGender($data['gender'], $reginfoArr['gender_must']);
            if ($checkGender != 1){
                $results['gender'] = $checkGender;
            }
        }

        //注册时展示 
        if ($reginfoArr['age_reg']) {
            //年龄
            $checkAge = $this->_checkAge($data['age'], $reginfoArr['age_must']);
            if ($checkAge != 1){
                $results['age'] = $checkAge;
            }
        }

        //PASSWORD
        $checkPw = $this->_checkPassword($data['password']);
        if ($checkPw != 1){
            $results['password'] = $checkPw;
        }
        //password too 
        if (!$data['password2']) {
            $results['password2'] = "请再次输入密码";
        }else{
            if ($data['password'] != $data['password2']){
                $results['password2'] = "两次输入的密码不一致";
            }
        }

        if (checkVerify($data['verify']) != true){
            $results['verify'] = '验证字符不正确, 请重新输入';
        }

        
        return $results;
    }

    /**
     * 登录账号检查
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function checkLoginAccount($value) {
        if (!$value){
            return "账号不能为空";
        }
        $membersDb = D("Member/Members");
        $row = $membersDb->getUserByAccount($value);
        if (!$row){
            return "账号不存在!";
        }
        return true;
    }

    /**
     * 检查密码是否正确
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function _checkLoginPw($account, $password){

        $loginNum = cookie('_loginNum_'.$account);
        if ($loginNum >= 6 && $account && $password){
            return "您的尝试次数过多,请稍后在试";
        }

        // d41d8cd98f00b204e9800998ecf8427e md5 之后的空值
        if ($password != 'd41d8cd98f00b204e9800998ecf8427e'){

            //echo $password;
            //用户数组
            $membersDb = D("Member/Members");
            $row = $membersDb->getUserByAccount($account);
            //print_r($row);
            if ($row['password'] != $password){
                //保护登录 启用
                $this->_protectLogin($account, 1);
                return "密码错误,请重新输入!";
                //清理验证码 重载
                echo "<script>parent.$(\"input[name='password']\").val('');</script>";
                
            }

        }else{
            return "密码不能为空";
        }
         

        
        return true;
    }


    /* 登陆保护
     * 密码错误 3次 输出验证码 错误6次锁定15分钟
     * */
    private function _protectLogin($account, $iserror=0) {
        //ISERROR 为 0  判断是否需要验证 为1时 验证错误次数+1

        $loginNum = cookie('_loginNum_'.$account);

        if ($iserror == 1){
            //大于6之后  不在记录COOKIE 直到自动消毁
            //小于6时 + 1
            if ($loginNum < 6){
                
                $loginNum = $loginNum + 1;
                //COOKIE 15分钟
                cookie('_loginNum_'.$account, $loginNum, 900);
            }
        }

        if ($loginNum >= 3){
            echo "<script type=\"text/javascript\">parent.showCaptcha(1);</script>";
        }else{
            echo "<script type=\"text/javascript\">parent.showCaptcha(2);</script>";
        }

        if ($iserror ==1 && $loginNum >= 6){
            if ($loginNum == 6){
                //  15分钟锁定须入库 防止手动清除COOKIE
                cookie('_loginNum_'.$account, 7, time() + 900);
            }
            echo "<script>parent.errorMsg(\"您的尝试次数过多,系统为了保护您的账号安全已经将您的账号<b>锁定15分钟</b>,如果您忘记密码<a href='".U('/member/forgot')."'>点击此处</a><b>找回密码</b>。或者在<b>15分钟</b>后重新尝试登录!\");</script>";
        }
        return;
        //echo $login_num;
        //print_r($_COOKIE);
        //return $login_num;
    }


    //网址是否合法
    public function checkWebAddr($data){
        if (!preg_match("/^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$/", $data))
        {
            return false;
        }
        return true;
    }

    /**
     * EMAIL 相关检查
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function _checkEmail($value, $isset=0) {
        //必须验证
        if ($isset) {
            if (!$value){
                return "请输入电子邮箱";
            }
        }

        //有值的情况下验证
        if ($value) {
            if (!checkEmailAddr($value)){
                return "电子邮箱格式不正确";
            }
            //EMAIL 重复性
            if (!$this->_checkUsed($value)){
                return "该EMAIL已被注册, 请使用其他EMAIL注册, 如果您是 $value, <a href=\"/member/login/?user=$value\">点击此处登录</a>";
            }
        }
        return true;
    }

    /**
     * 检查用户名是否合法
     * @param  [type]  $value [description]
     * @param  integer $isset [description]
     * @return [type]         [description]
     */
    public function _checkUsername($value, $isset=0) {
        //必须验证
        if ($isset) {
            if (!$value){
                return "请输入用户名";
            }
        }

        //有值的情况下验证
        if ($value) {
            if (checkFloat($value)){
                return "用户名不能全为数字";
            }
            if (!checkLengthBetween($value, 4, 20)){
                return "用户名长度需在 4-20 个字符";
            }
            if (!checkSpecialCh($value)){
                return "用户名含有非法字符（仅可以使用字母、数字、下划线)";
            }
            //USERNAME 重复性
            if (!$this->_checkUsed($value)){
                return "该名称已被注册, 请使用其他名称注册, 如果您是 $value, <a href=\"/member/login/?user=$value\">点击此处登录</a>";
            }
        }

        return true;
    }

    /**
     * 检查手机号格式
     * @param  [type]  $value [description]
     * @param  integer $isset [description]
     * @return [type]         [description]
     */
    public function _checkPhone($value, $isset=0) {
        //echo $value;
        //必须验证
        if ($isset) {
            if (!$value){
                return "请输入手机号";
            }
        }

        //有值的情况下验证
        if ($value) {
            if (!checkPhoneNo($value)){
                return "手机号格式不正确";
            }
            //重复性
            if (!$this->_checkUsed($value)){
                return "该手机号已被注册, 请使用其他手机号注册, 如果您是 $value, <a href=\"/member/login/?user=$value\">点击此处登录</a>";
            }
        }
        return true;
    }

    /**
     * 检查昵称合法性
     * @param  [type]  $value [description]
     * @param  integer $isset [description]
     * @return [type]         [description]
     */
    public function _checkRealname($value, $isset=0) {
        //echo $value;
        //必须验证
        if ($isset) {
            if (!$value){
                return "请输入昵称";
            }
        }

        //有值的情况下验证 验证中文 
        // if ($value) {
        //     if (!checkChinese($value)){
        //         return "昵称必须为中文";
        //     }
        // }
        return true;
    }

    /**
     * 检查性别是否输入
     * @param  [type]  $value [description]
     * @param  integer $isset [description]
     * @return [type]         [description]
     */
    public function _checkGender($value, $isset=0) {
        //echo $value;
        //必须验证
        if ($isset) {
            if (!$value){
                return "请选择性别";
            }
        }

        return true;
    }

    /**
     * 检查年龄输入
     * @param  [type]  $value [description]
     * @param  integer $isset [description]
     * @return [type]         [description]
     */
    public function _checkage($value, $isset=0) {
        //echo $value;
        //必须验证
        if ($isset) {
            if (!$value){
                return "请输入年龄";
            }
        }

        //有值时判断 是否正确
        if ($value) {
            if (!checkFloat($value)) {
                return "年龄必须为数字";
            }
            if ($value > 100) {
                return "请输入真实年龄";
            }
        }
        return true;
    }

    /**
     * 检查账号是否已存在
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    private function _checkUsed($value)
    {
        //重复性
        $membersDb = D("Member/Members");
        if ($membersDb->getUserByAccount($value)){
            return false;
        }
        return true;
    }

    /**
     * 密码相关检查
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function _checkPassword($value) {
        if (!$value){
            return "请输入密码";
        }
        if (checkFloat($value)){
            return  "密码不能全为数字";
        }

        if (!CheckLengthBetween($value, 6, 50)){
            return "密码长度需为6-50个字符";
        }
        return true;
    }


	/**
	 * 是否已经登录 SESSION
	 * @return 返回USER ARRAY 
	 */
	public function getIsLogin()
	{
		//登录的
		$uid = session('uid');
        
        $membersDb = D("Member/Members");
        if (!$uid) {
            $memberCookie = unserialize(stripslashes(cookie('_member')));

            //读取COOKIE 进行登陆
            if ($memberCookie){
                $islogin = $this->_checkLoginPw($memberCookie['email'], $memberCookie['password']);
                //如果密码正确
                if ($islogin == 1){
                    //设置登陆
                    if ($this->setLoginNow($memberCookie['uid'], 1)){
                        $loginArr = $membersDb->getProfile($memberCookie['uid']);

			            return $loginArr;
                    }
                }else{
                    //没有COOKIE或密码不正确  返回失败 清除COOKIE
                    $this->unsetLogin();
                }
            }
            //登录失败
            $loginArr = false;
        }else{

            $loginArr =  $membersDb->getProfile($uid);
        }

        //被BAN了
        if ($loginArr['ban'] == 2) {
            $this->error('您的账号因违反网站条例, 已被禁止访问');
            $this->unsetLogin();
            return false;
        }


        return $loginArr;
	}


    /**
     * 销毁登录 SESSION 
     * 如果 有COOKIE清除
     * @return [type] [description]
     */
    public function unsetLogin() {
        
        session('uid', null);
        //清理COOKIE
        cookie("_member", null);
        return true;
    }

    /**
     * 设置新密码
     * @param [type] $newPassword [description]
     */
    public function setNewPassword($uid, $newPassword)
    {
        //新密码 更新
        $passwordData = array(
            'password'  => md5($newPassword),    
        );
        //  会员表
        $memberDb = D('Member/Members');
        $memberDb->setUpdate($passwordData, $uid);

        //更新完成后注销登录
        $this->unsetLogin();
        return true;
    }


}