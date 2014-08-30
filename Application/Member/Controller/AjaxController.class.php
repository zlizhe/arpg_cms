<?php
namespace Member\Controller;
use Common\Controller\PublicController;

/**
 * 	MEMBER AJAX 模块
 * 	2014.7.14 @lizhe
 */
class AjaxController extends PublicController {


	public function _initialize()
	{
        //执行开始
        $this->_run();
    }

    /**
     * 输出验证字符
     * @return [type] [description]
     */
    public function getCaptcha()
    {
        return getVerify();
    }

    /**
     * 账号登录
     */
    public function setLogin()
    {

        $loginArr['email'] = I('email');
        $loginArr['password'] = I('password');
        $loginArr['autologin'] = I('autologin');
        $loginArr['verify'] = I('verify');
        $ref = urlencode(I('ref', '/'));

        //验证输入，
        $memberLogic = D('Member/Member', 'Logic');
        $error = $memberLogic->getLoginCheck($loginArr);
        //输出错误 信息
        if ($error) {
            foreach ($error as $key => $value) {
            	//echo $value;
            	if ($value == 1) {
            		//正确
            		echo "<script>parent.hidePopError('".$key."')</script>";
            	}else{
            		//错误 SHOW POP 方法显示 错误 
            		echo "<script>parent.showPopError('".$key."', '".$value."')</script>";
            	}
            }

        }else{
            //登录成功 跳转
            $membersDb = D('Member/Members');
            $memberArr = $membersDb->getUserByAccount($loginArr['email']);

            //1个月免登录
            // 设置登录
            $memberLogic->setLoginNow($memberArr['uid'], $loginArr['autologin']);
            echo "<script type=\"text/javascript\">parent.successMsg(\"登录成功, 正在跳转...\", \"".$ref."\");</script>";
            
        }
        exit();
    }


    /**
     * 编辑 个人资料
     */
    public function setProfile()
    {
        //登录用户
        $loginArr = $this->_Guser;
        //未登录
        if (!$loginArr) {
            echo "<script>parent.nowLogin();</script>";
            exit();
        }

        $ref = urlencode(I('ref', '/'));

        $realname = I('post.realname', '');
        $gender = intval(I('post.gender', 0));
        $age = intval(I('post.age', 0));

        //注册相关的控制
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoArr = $reginfoDb->get();
        
        $err = 0;
        //验证输入，
        $memberLogic = D('Member/Member', 'Logic');

        //REALNAME
        $checkRealname = $memberLogic->_checkRealname($realname, $reginfoArr['realname_must']);
        if ($checkRealname != 1){
            echo "<script>parent.showPopError('realname', '".$checkRealname."')</script>";
            $err = 1;
        }

        //性别 
        $checkGender = $memberLogic->_checkGender($gender, $reginfoArr['gender_must']);
        if ($checkGender != 1){
            echo "<script>parent.showPopError('genderInput', '".$checkGender."')</script>";
            $err = 1;
        }

        //年龄
        $checkAge = $memberLogic->_checkAge($age, $reginfoArr['age_must']);
        if ($checkAge != 1){
            echo "<script>parent.showPopError('age', '".$checkAge."')</script>";
            $err = 1;
        }

        //报错
        if ($err) {
            exit();
        }

        //存储
        $profileDb = D('Member/MembersProfile');
        $data = array(
            'realname'  => $realname,
            'gender'    => $gender,
            'age'       => $age,
        );
        $profileDb->setUpdate($data, $loginArr['uid']);
        
        
        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }



    /**
     * 上传头像图片
     * @return [type] [description]
     */
    public function upAvatarSmart()
    {
        $smartAva = $_FILES['smart_ava'];

        if (!$smartAva) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('请选择一个图片文件')</script>";
            exit();
        }
        if ($smartAva['type'] != 'image/jpeg') {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('必须为 JPEG 格式图片')</script>";
            exit();
        }
        if ($smartAva['size'] > (1024 * 1000 * 2)) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('请上传小于 2MB 的图片文件')</script>";
            exit();
        }

        $loginArr = $this->_Guser;

        $now = getTime();
        $newName = $loginArr['uid'].'.jpg';
        //图片存储位置
        $newDir = $loginArr['uid'].'/';
        $pathDir = getcwd()."/uploads/avatar/".$newDir;
        //创建文件夹
        creatDir($pathDir);
        //图片路径
        $newImg = $pathDir.$newName;

        //开始上传
        if (!move_uploaded_file($smartAva['tmp_name'], $newImg)) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('上传失败, 请刷新后重试')</script>";
            exit();
        }else{
            //显示 图片进一步处理
            $url = "/dialog/dialog/avatarUpImg?imgpath=".$this->_Gset['IMG_AVATAR'].$newDir.$newName;
            echo "<script>parent.showWindow('".$url."');</script>";
            exit();
        }
        
    }


    /**
     * 切割图片/图片后续处理
     * @return [type] [description]
     */
    public function cutImg()
    {   
        //没有REF不跳转 将图片设置在页面上
        $ref = I('post.ref', '');

        //$pimg = I('post.img');
        $x = intval(I('post.x', 0));
        $y = intval(I('post.y', 0));
        $w = intval(I('post.w'));
        $h = intval(I('post.h'));
        $pwidth = intval(I('post.pwidth'));
        $pheight = intval(I('post.pheight'));

        $setLogic = D('Set/Setservice', 'Logic');
        //当图片过大被缩放至弹窗大小时 按比例约束坐标
        $imagesRes = getimagesize($img);
        //print_r($imagesRes);
        $wp = 1;
        $hp = 1;
        // if ($imagesRes[0] > $pwidth) {
        //     $wp = $imagesRes[0] / $pwidth;
        // }
        // if ($imagesRes[1] > $pheight) {
        //     $hp = $imagesRes[1] / $pheight;
        // }
        //echo $w;
        
        $loginArr = $this->_Guser;
        // Settings
        $avadir = getcwd().'/uploads/avatar/'.$loginArr[uid];
        //creatdir($avadir);
      
        //图片
        $getfile = '/'.$loginArr[uid].'.jpg';   

        //2种大小的头像图片
        $big_file = str_replace('.jpg', '_big.jpg', $getfile);
        $small_file = str_replace('.jpg', '_small.jpg', $getfile);

        //按区域剪裁
        if ($w && $h) {

            $targ_w = $targ_h = 150; //保存的图片的大小
            $jpeg_quality = 100;


            $img_r = imagecreatefromjpeg($avadir.$getfile);
            $dst_r = imagecreatetruecolor($targ_w, $targ_h);
            //切图
            imagecopyresampled($dst_r, $img_r, 0, 0, $x * $wp, $y * $hp,
                            $targ_w, $targ_h, $w * $wp, $h * $hp);

            //存图
            $setLogic->saveImg($getfile, $dst_r, $avadir.$big_file);

            //有需要在进行缩放
            $setLogic->cutphoto($avadir.$big_file, $avadir.$small_file, 50, 50);

            //销毁图像
            imagedestroy($dst_r);
        }else{
            //如果没有划区域
            //small
            $setLogic->cutphoto($avadir.$getfile, $avadir.$small_file, 50, 50);
            //medium
            $setLogic->cutphoto($avadir.$getfile, $avadir.$big_file, 150, 150);
        }

        #### 处理完毕 需要设置OSS 在这里上传 ####
        // //OSS 上传
        // $oss_sdk_service = new ALIOSS();
        // //设置是否打开curl调试模式
        // $oss_sdk_service->set_debug_mode(FALSE);
        // //BUCKET NAME
        // $bucket = "ripppple-avatar";
        // $oss_sdk_service->create_object_dir($bucket, $oss_path);
        // //SMALL
        // $response = $oss_sdk_service->upload_file_by_file($bucket, $oss_path.$getfile, $realFile);



        //操作成功
        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"/space/setup/app/2\");</script>";
        exit();
    }


    /**
     * 编辑 账号资料
     */
    public function setMemberData()
    {
        
        //登录用户
        $loginArr = $this->_Guser;
        //未登录
        if (!$loginArr) {
            echo "<script>parent.nowLogin();</script>";
            exit();
        }

        $ref = urlencode(I('ref', '/'));

        $username = I('post.username', '');
        $email = I('post.email', '');
        $phone = I('post.phone', '');
        $password = md5(I('post.password', ''));

        //设置新密码
        $newPassword = I('post.newPassword', '');
        $newPassword2 = I('post.newPassword2', '');


        //注册相关的控制
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoArr = $reginfoDb->get();
        
        $err = 0;
        //验证输入，
        $memberLogic = D('Member/Member', 'Logic');

        //是否和原账号相同
        $memberDb = D('Member/Members');
        $memberArr = $memberDb->get($loginArr['uid']);

        //USERNAME 用户名和原来不同 或 没有输入值（验证强制输入） 验证
        if (($username != $memberArr['username']) || !$username) {
            $checkUsername = $memberLogic->_checkUsername($username, $reginfoArr['username_must']);
            if ($checkUsername != 1){
                echo "<script>parent.showPopError('username', '".$checkUsername."')</script>";
                $err = 1;
            }
        }


        //EMAIL
        if (($email != $memberArr['email']) || !$email) {
            $checkEmail = $memberLogic->_checkEmail($email, $reginfoArr['email_must']);
            if ($checkEmail != 1){
                echo "<script>parent.showPopError('email', '".$checkEmail."')</script>";
                $err = 1;
            }
        }

        //Phone
        if (($phone != $memberArr['phone']) || !$phone) {
            $checkPhone = $memberLogic->_checkPhone($phone, $reginfoArr['phone_must']);
            if ($checkPhone != 1){
                echo "<script>parent.showPopError('phone', '".$checkPhone."')</script>";
                $err = 1;
            }
        }

        //确定账号
        if ($loginArr['username']) {
            $account = $loginArr['username'];
        }elseif ($loginArr['email']){
            $account = $loginArr['email'];
        }else{
            $account = $loginArr['phone'];
        }

        //有账号
        if ($account) {
            //检查密码是否正确
            $checkPassword = $memberLogic->_checkLoginPw($account, $password);
            if ($checkPassword != 1){
                echo "<script>parent.showPopError('password', '".$checkPassword."')</script>";
                $err = 1;
            }
        }else{
            //没有账号 （一般无此情况）
            exit();
        }

        //新密码相关
        if ($newPassword) {
            //密码安全性
            $checkNewPassword = $memberLogic->_checkPassword($newPassword);
            if ($checkNewPassword != 1){
                echo "<script>parent.showPopError('newPassword', '".$checkNewPassword."')</script>";
                $err = 1;
            }

            //两次输入一致性
            if ($newPassword != $newPassword2) {
                echo "<script>parent.showPopError('newPassword2', '两次输入的密码不相同')</script>";
                $err = 1;
            }
        }

        //报错
        if ($err) {
            exit();
        }

        //存储账号资料
        $data = array(
            'username'  => $username,
            'email'     => $email,
            'phone'     => $phone,
        );
        $memberDb->setUpdate($data, $loginArr['uid']);

        //修改新密码
        if ($newPassword) {

            //写入新密码并注销登录
            $memberLogic->setNewPassword($loginArr['uid'], $newPassword);
        }


        //操作成功
        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"/space/setup/app/3\");</script>";
        exit();
    }

    /**
     * 找回密码 设置新密码
     */
    public function setNewPassword()
    {

        //SESSION 
        $forgotAccount = session('forgotAccount');
        //没有被找回的账号
        if (!$forgotAccount) {
            echo "<script>parent.errorMsg('操作失败, 请重新找回密码。并在2小时内完成操作')</script>";
            exit();
        }

        //设置新密码
        $newPassword = I('post.newPassword', '');
        $newPassword2 = I('post.newPassword2', '');


        //验证输入，
        $memberLogic = D('Member/Member', 'Logic');

        $err = 0;
        //密码安全性
        $checkNewPassword = $memberLogic->_checkPassword($newPassword);
        if ($checkNewPassword != 1){
            echo "<script>parent.showPopError('newPassword', '".$checkNewPassword."')</script>";
            $err = 1;
        }

        //两次输入一致性
        if ($newPassword != $newPassword2) {
            echo "<script>parent.showPopError('newPassword2', '两次输入的密码不相同')</script>";
            $err = 1;
        }

        //报错
        if ($err) {
            exit();
        }

        //写入新密码并注销登录
        $memberLogic->setNewPassword($forgotAccount, $newPassword);

        //操作成功
        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"/member/login\");</script>";
        exit();
    }

}