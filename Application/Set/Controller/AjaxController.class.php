<?php
namespace Set\Controller;
use Common\Controller\PublicController;

/**
 * 	SET AJAX 模块
 * 	2014.7.21 @lizhe
 */
class AjaxController extends PublicController {


	public function _initialize()
	{
        //执行开始
        $this->_run();

        //是否有权限 [后台专用]
        $this->_getPurview(MODULE_NAME, CONTROLLER_NAME, ACTION_NAME, false);
    }

    /**
     * 编辑会员信息
     * 可以编辑 密码 与 会员组 其他为只读
     */
    public function setEditUser()
    {
        //更改的角色ID
        $uid = I('post.uid');
        $ref = urlencode(I('post.ref', '/set'));

        $password = I('post.password', '');
        $group = I('post.group', '');

        $data = '';
        if ($password) {
            $data['password'] = md5($password);
        }
        if ($group) {
            $data['groupid'] = $group;
        }

        //修改数据 
        if ($data) {
            $membersDb = D('Member/Members');
            $membersDb->setUpdate($data, $uid);
        }
        // print_r($data);
        // exit();

        echo "<script type=\"text/javascript\">parent.successMsg(\"修改成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }

    /**
     * 添加新用户
     * @param string $value [description]
     */
    public function setAddUser()
    {
        $realname = I('post.realname', '');
        $username = I('post.username', '');
        $email = I('post.email', '');
        $phone = I('post.phone', '');
        //未设置密码 使用空值MD5
        $password = md5(I('post.password', ''));
        $groupid = I('post.group', '');
        $ref = urlencode(I('post.ref', '/set'));

        $err = 0;
        //必须 要设置一个 账号用于登录
        if (!$username && !$email && !$phone) {
            echo "<script>parent.errorMsg('用户名/电子邮件/手机号码 必须填写一个')</script>";
            exit();
        }else{

            //账号是否重复
            $membersDb = D("Member/Members");
            //检查用户名
            if ($username) {
                if ($membersDb->getUserByAccount($username)){
                    echo "<script>parent.showPopError('username', '该用户名已被注册, 请使用其他用户名')</script>";
                    $err = 1;
                }
            }
            //检查邮件
            if ($email) {
                if ($membersDb->getUserByAccount($email)){
                    echo "<script>parent.showPopError('email', '该邮箱已被注册, 请使用其他邮箱')</script>";
                    $err = 1;
                }
            }
            //检查手机
            if ($phone) {
                if ($membersDb->getUserByAccount($phone)){
                    echo "<script>parent.showPopError('phone', '该手机号已被注册, 请使用其他手机号')</script>";
                    $err = 1;
                }
            }


        }

        //报错
        if ($err) {
            exit();
        }

        //新增 
        $saveData = array(
            'username' => $username,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
            'groupid'  => $groupid,
            'status'   => 1,
            'regip'    => '127.0.0.1',
            'regdate'  => getTime(),
            //profile
            'realname' => $realname,
            'gender'   => '',
            'age'      => '',
        );
        

        //创建账号
        $memberLogic = D('Member/Member', 'Logic');
        $onReg = $memberLogic->setRegUser($saveData);

        //print_r($saveData);
        //exit();

        if ($onReg) {
            //exit();
            echo "<script type=\"text/javascript\">parent.successMsg(\"账号创建成功, 正在跳转...\", \"".$ref."\");</script>";
        }

        exit();
    }

    /**
     * BAN USER
     */
    public function setBan()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }

        $uid = I('uid');

        //1为恢复 2为 BAN
        $type = I('type', 2);

        $membersDb = D("Member/Members");

        if ($type == 1) {
            //BAN 状态 为 2
            $data['ban'] = 1;
        }else{
            $data['ban'] = 2;
        }
        
        $res = $membersDb->setUpdate($data, $uid);

        if ($res) {
            $data['status'] = 'success';
        }else{
            $data = array(
                'status'    => 'error',
                'msg'       => '内部错误, 请刷新后重试',
            );
        }
        
        $this->ajaxReturn($data);
    }

    /**
     * 编辑 用户组权限  角色权限
     */
    public function setEditGroup()
    {
        
        
        $ref = urlencode(I('post.ref', '/set'));

        //操作类型
        $ac = I('post.ac', 'add');
        $value = I('post.value');
        $note = I('post.note');
        $actions = I('post.actions', '');

        //必须 要设置一个 账号用于登录
        if (!$value) {
            echo "<script>parent.showPopError('value', '请输入一个角色名称')</script>";
            exit();
        }

        //如果 有权限 操作 serialize 没有则留空 写入
        if ($actions) {
            $actions = serialize($actions);
        }

        //写入数据
        $data = array(
            'value'      => $value,
            'note'       => $note,
            'in_actions' => $actions,
        );

        $groupDb = D('Member/MembersGroup');

        //新增内容
        if ($ac == 'add') {
            $res = $groupDb->set($data);
        }

        //编辑内容
        if ($ac == 'edit') {
            $gid = intval(I('post.gid'));
            $res = $groupDb->setUpdate($data, $gid);
        }


        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }

    /**
     * 删除角色 [用户组]
     */
    public function setDelGroup()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }

        $ref = urlencode(I('ref', '/set'));
        $gid = I('id');


        //GROUP 表
        $groupDb = D('Member/MembersGroup');
        $res = $groupDb->del($gid);

        //操作成功
        if ($res) {
            $data['status'] = 'success';
        }else{
            $data = array(
                'status'    => 'error',
                'msg'       => '内部错误, 请刷新后重试',
            );
        }
        $this->ajaxReturn($data);
    }

    /**
     * 编辑权限
     */
    public function setEditCompetence()
    {
        
        $ref = urlencode(I('post.ref', '/set'));

        //操作类型
        $ac = I('post.ac', 'add');
        $value = I('post.value');
        $action = I('post.action');
        $upid = intval(I('post.upid'));

        $err = 0;
        //未填写完整
        if (!$value) {
            echo "<script>parent.showPopError('value', '请输入一个权限描述')</script>";
            $err = 1;
        }
        if (!$action) {
            echo "<script>parent.showPopError('action', '请输入格式正确的操作名称')</script>";
            $err = 1;
        }

        //有错误 停止
        if ($err == 1) {
            exit();
        }

        $competenceDb = D('Member/MembersCompetence');
        //权限数据
        $data = array(
            'value'  => $value,
            'action' => $action,
            'upid'   => $upid,
        );

        //新增权限
        if ($ac == 'add') {
            $res = $competenceDb->set($data);
        }

        //编辑权限
        if ($ac == 'edit') {
            $cid = I('post.cid');
            $res = $competenceDb->setUpdate($data, $cid);
        }
        
        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }

    /**
     * 删除权限
     */
    public function setDelCompetence()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }

        $ref = urlencode(I('ref', '/set'));
        $cid = I('id');


        //GROUP 表
        $competenceDb = D('Member/MembersCompetence');
        $res = $competenceDb->del($cid);

        //操作成功
        if ($res) {
            $data['status'] = 'success';
        }else{
            $data = array(
                'status'    => 'error',
                'msg'       => '内部错误, 请刷新后重试',
            );
        }
        $this->ajaxReturn($data);
    }


    /**
     * 编辑 导航设置
     */
    public function setEditNav()
    {
        $ref = urlencode(I('post.ref', '/set'));

        //操作类型
        $ac = I('post.ac', 'add');
        $value = strip_tags(I('post.value'));
        $title = strip_tags(I('post.title', ''));
        $link  = I('post.link');
        // $index = intval(I('post.index', 0));
        $target = intval(I('post.target', 0));
        $upid = intval(I('post.upid', 0));

        $err = 0;
        //未填写完整
        if (!$value) {
            echo "<script>parent.showPopError('value', '请输入导航名称')</script>";
            $err = 1;
        }
        if (!$link) {
            echo "<script>parent.showPopError('link', '请输入链接地址')</script>";
            $err = 1;
        }

        //有错误 停止
        if ($err == 1) {
            exit();
        }

        //LEVEL相关 最多允许2级导航深度
        if ($upid == 0) {
            $level = 0;
        }else{
            $level = 1;
        }

        //数据组
        $data = array(
            'value'  => $value,
            'title'  => $title,
            'link'   => $link,
            'target' => $target,
            'upid'   => $upid,
            'level'  => $level,
        );

        //NAV DB ...
        $navDb = D('Service/ServiceNav');

        //写入新值
        if ($ac == 'add') {
            $res = $navDb->set($data);
        }

        //更新现有值
        if ($ac == 'edit') {
            $nid = intval(I('post.nid'));
            $res = $navDb->setUpdate($data, $nid);
        }


        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 批量编辑  导航设置
     */
    public function setEditAllNav()
    {
        $ref = urlencode(I('post.ref', '/set'));

        $links = I('post.link');
        $apps = I('post.app');
        $targets = I('post.target');
        $sorts = I('post.sort');
        $dels = I('post.del');
        $ids = I('post.id');


        //NAV DB ...
        $navDb = D('Service/ServiceNav');


        //首先执行删除操作
        if ($dels) {
            //数组转换
            foreach ($dels as $key => $value) {
                $delIds .= $value.',';
            }

            $navDb->del($delIds);
        }else{
            //没有删除操作 则更新 防止 已删除的 在更新ERROR

            //执行批量更新
            foreach ($ids as $key => $value) {
                $data = array(
                    'link'   => $links[$ids[$key]],
                    'app'    => $apps[$ids[$key]],
                    'target' => $targets[$ids[$key]],
                    'sort'   => $sorts[$ids[$key]],
                );
                $navDb->setUpdate($data, $ids[$key]);
            }

            //设置为首页
            $index = intval(I('post.index'));
            if ($index) {
                // $navArr = $navDb->get($index);
                //SETTING DB...
                $settingDb = D('Service/ServiceSetting');
                $data = array(
                    'site_home'    => $index,
                );
                //更新配置选项
                $settingDb->setUpdate($data);
            }
        }




        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 添加新主题
     */
    public function setAddThemes()
    {
        $ref = urlencode(I('post.ref', '/set'));
        
        $ac = strip_tags(I('post.ac'));
        $name = strip_tags(I('post.name'));
        $path_name = strip_tags(I('post.path_name'));

        $err = 0;
        if (!$name) {
            echo "<script>parent.showPopError('name', '请输入主题名称')</script>";
            $err = 1;
        }

        if (!$path_name) {
            echo "<script>parent.showPopError('path_name', '请输入文件夹名称')</script>";
            $err = 1;
        }

        //themes db ...
        $themesDb = D("Service/ServiceThemes");
        $themesArr = $themesDb->getByPath($path_name);

        // print_r($themesArr);
        // exit();
        if ($themesArr) {
            echo "<script>parent.showPopError('path_name', '该名称已经存在, 请更换名称重试')</script>";
            $err = 1;
        }

        //主题文件夹 路径
        $newThemesPath = getcwd().'/'.C('VIEW_PATH').$path_name;

        //导入已下载 的主题文件
        if ($ac == 1) {
            if (!is_dir($newThemesPath)) {
                echo "<script>parent.showPopError('path_name', '主题文件夹不存在, 请检查输入')</script>";
                $err = 1;
            }
        }

        //报错
        if ($err) {
            exit();
        }

        //开发者创建主题
        if ($ac == 2) {
            
            //创建默认文件
            $themeLogic = D('Service/Theme', 'Logic');
            $themeLogic->creatDefault($newThemesPath);
        }

        //写入主题
        $data = array(
            'name'      => $name,
            'path_name' => $path_name,
        );
        $res = $themesDb->set($data);

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }

    /**
     * 清理缓存 
     * @return [type] [description]
     */
    public function rmCache()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }

        $ref = urlencode(I('ref', '/set'));

        //清理文件夹名称
        $type = I('type', 'all');

        //全部RUNTIME
        if ($type == 'all') {
            $type = 'Runtime';
        }else{
            $type = 'Runtime/'.$type;
        }
        //0777
        @chmod($type, 511);

        // echo $type;
        // exit();

        //清理缓存
        $res = getRmCache('', $type);
        //操作成功
        if ($res == true) {
            $data['status'] = 'success';
        }else{
            $data = array(
                'status'    => 'error',
                'msg'       => '操作失败, 请检查 /Application/Runtime 目录是否有可写权限',
            );
        }
        $this->ajaxReturn($data);
    }


    /**
     * 批量编辑 主题相关
     */
    public function setEditThemes()
    {

        $ref = urlencode(I('post.ref', '/set'));

        $dels = I('post.del');

        //themes db ...
        $themesDb = D('Service/ServiceThemes');
        //卸载主题
        if ($dels) {
            //是否删除文件夹
            //$del_folder = intval(I('post.del_folder', 0));

            //数组转换
            foreach ($dels as $key => $value) {
                $delIds .= $value.',';
            }

            //删除主题(S)
            $themesDb->del($delIds);

            // //删除文件夹
            // if ($del_folder) {
            //     //PATH 名称
            //     $path_names = I('post.path');

            //     print_r($dels);
            //     exit();
            //     //THEMES LOGIC
            //     $themesLogic = D('Service/Themes', 'Logic');
            //     foreach ($path_names as $key => $value) {
            //         $themeLogic->rmThemesFolder($value[$dels[$key]]);
            //     }
                
                
            // }
        }else{
            //设置默认主题 防止删除的主题被设成默认 ，造成默认主题丢失
            $theme = intval(I('post.theme', 0));

            if ($theme) {
                //主题详情
                $themesArr = $themesDb->get($theme);
                //SETTING DB...
                $settingDb = D('Service/ServiceSetting');
                $data = array(
                    'site_themes'    => $themesArr['path_name'],
                );
                //写入新主题
                $settingDb->setUpdate($data);

                //更新缓存
                echo "<script>parent.showWindow('/set/dialog/rmCache/ref/%252Fset%252Fthemes')</script>";
                exit();
            }
        }


        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 网站设置提交
     */
    public function setSetting()
    {
        $ref = urlencode(I('ref', '/set'));

        $site_name = strip_tags(I("post.site_name"));
        $site_url = strip_tags(I("post.site_url"));
        $mail_server = strip_tags(I("post.mail_server"));
        $mail_username = strip_tags(I("post.mail_username"));
        $mail_password = strip_tags(I("post.mail_password"));
        $img_url = strip_tags(I("post.img_url"));
        $img_avatar = strip_tags(I("post.img_avatar"));
        $analytics = I("post.analytics");
        $siteopen = intval(I("post.siteopen"));

        $err = 0;
        if (!$site_name) {
            echo "<script>parent.showPopError('site_name', '请输入网站名称')</script>";
            $err = 1;
        }
        if (!$site_url) {
            echo "<script>parent.showPopError('site_url', '请输入网站域名')</script>";
            $err = 1;
        }
        if (!$mail_server) {
            echo "<script>parent.showPopError('mail_server', '请输入邮件服务器')</script>";
            $err = 1;
        }
        if (!$mail_username) {
            echo "<script>parent.showPopError('mail_username', '请输入发件邮箱')</script>";
            $err = 1;
        }
        if (!$mail_password) {
            echo "<script>parent.showPopError('mail_password', '请输入发件密码')</script>";
            $err = 1;
        }
        if (!$img_url) {
            echo "<script>parent.showPopError('img_url', '请输入图片域名或路径')</script>";
            $err = 1;
        }
        if (!$img_avatar) {
            echo "<script>parent.showPopError('img_avatar', '请输入头像域名或路径')</script>";
            $err = 1;
        }

        //报错
        if ($err == 1) {
            exit();
        }

        //更新数据
        $settingDb = D('Service/ServiceSetting');
        //配置数组
        $data = array(
            'site_name'     => $site_name,
            'site_url'      => $site_url,
            'mail_server'   => $mail_server,
            'mail_username' => $mail_username,
            'mail_password' => $mail_password,
            'img_url'       => $img_url,
            'img_avatar'    => $img_avatar,
            'analytics'     => $analytics,
            'siteopen'      => $siteopen,
        );
        $settingDb->setUpdate($data);

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 注册控制相关 更新
     * @param string $value [description]
     */
    public function setReginfo()
    {
        $ref = urlencode(I('ref', '/set'));

        //username
        $data['username_true'] = intval(I('post.username_true'));
        $data['username_reg'] = intval(I('post.username_reg'));
        $data['username_must'] = intval(I('post.username_must'));
        //email
        $data['email_true'] = intval(I('post.email_true'));
        $data['email_reg'] = intval(I('post.email_reg'));
        $data['email_must'] = intval(I('post.email_must'));
        //phone
        $data['phone_true'] = intval(I('post.phone_true'));
        $data['phone_reg'] = intval(I('post.phone_reg'));
        $data['phone_must'] = intval(I('post.phone_must'));
        //realname
        $data['realname_true'] = intval(I('post.realname_true'));
        $data['realname_reg'] = intval(I('post.realname_reg'));
        $data['realname_must'] = intval(I('post.realname_must'));
        //gender
        $data['gender_true'] = intval(I('post.gender_true'));
        $data['gender_reg'] = intval(I('post.gender_reg'));
        $data['gender_must'] = intval(I('post.gender_must'));
        //age
        $data['age_true'] = intval(I('post.age_true'));
        $data['age_reg'] = intval(I('post.age_reg'));
        $data['age_must'] = intval(I('post.age_must'));
        // //idno
        // $data['idno_true'] = intval(I('post.idno_true'));
        // $data['idno_reg'] = intval(I('post.idno_reg'));
        // $data['idno_must'] = intval(I('post.idno_must'));
        //area
        $data['area_true'] = intval(I('post.area_true'));
        $data['area_reg'] = intval(I('post.area_reg'));
        $data['area_must'] = intval(I('post.area_must'));

        //用户名/邮箱/手机 必选一个
        if ((!$data['username_reg'] || !$data['username_must'] || !$data['username_true']) && 
            (!$data['email_reg'] || !$data['email_must'] || !$data['email_true']) && 
            (!$data['phone_reg'] || !$data['phone_must'] || !$data['phone_true'])) {
            echo "<script>parent.errorMsg('必须在 启用和注册时展示及必须填写 启用 用户名/邮箱/手机号码 其中一项')</script>";
            exit();
        }

        //更新数据
        $reginfoDb = D('Service/ServiceReginfo');
        $reginfoDb->setUpdate($data);

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 更新/新增  分类
     */
    public function setEditCategory()
    {
        $ref = urlencode(I('post.ref', '/set'));

        //操作类型
        $ac = I('post.ac', 'add');
        $value = strip_tags(I('post.value'));
        $path_name = strip_tags(I('post.path_name', ''));
        $link  = I('post.link');
        $upid = intval(I('post.upid', 0));

        //添加至主导航
        $onNav = intval(I('post.onNav', 0));

        $err = 0;
        //未填写完整
        if (!$value) {
            echo "<script>parent.showPopError('value', '请输入分类名称')</script>";
            $err = 1;
        }
        //目录规范
        $path_name = str_replace('/', '', $path_name);

        if (!$path_name) {
            echo "<script>parent.showPopError('path_name', '请输入自定义目录名称')</script>";
            $err = 1;
        }

        //CAT DB ...
        $catDb = D('Home/CmsCategory');

        //目录不重复
        $catArr = $catDb->getByPath($path_name);
        if ($catArr) {
            echo "<script>parent.showPopError('path_name', '目录名称重复, 换一个名称吧')</script>";
            $err = 1;
        }

        //目录非法 保留关键字
        $protectedKey = array('article', 'search', 'ajax', 'set', 'member', 'service', 'dialog', 'home');
        if (in_array(strtolower($path_name), $protectedKey)) {
            echo "<script>parent.showPopError('path_name', '目录名称为系统保留字, 换一个名称吧')</script>";
            $err = 1;
        }
 
        //报错
        if ($err) {
            exit();
        }


        //数据组
        $data = array(
            'value'     => $value,
            'path_name' => $path_name,
            'upid'      => $upid,
        );



        //写入新值
        if ($ac == 'add') {
            $res = $catDb->set($data);
        }

        //更新现有值
        if ($ac == 'edit') {
            $cid = intval(I('post.cid'));
            $res = $catDb->setUpdate($data, $cid);
        }

        //需要添加至主导航
        if ($onNav) {
            $navData = array(
                'value' => $value,
                'link'  => '/'.$path_name,
            );
            //NAV 表
            $navDb = D('Service/ServiceNav');
            $navDb->set($navData);
        }

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 批量删除 分类 / 更新排序
     */
    public function setEditAllCat()
    {
        $ref = urlencode(I('post.ref', '/set'));

        $path_names = I('post.path_name');
        $apps = I('post.app');
        $sorts = I('post.sort');
        $dels = I('post.del');
        $ids = I('post.id');


        //NAV DB ...
        $catDb = D('Home/CmsCategory');


        //首先执行删除操作
        if ($dels) {
            //数组转换
            foreach ($dels as $key => $value) {
                $delIds .= $value.',';
            }

            $catDb->del($delIds);
        }else{
            //没有删除操作 则更新 防止 已删除的 在更新ERROR

            //执行批量更新
            foreach ($ids as $key => $value) {
                $data = array(
                    'path_name' => $path_names[$ids[$key]],
                    'app'       => $apps[$ids[$key]],
                    'sort'      => $sorts[$ids[$key]],
                );
                $catDb->setUpdate($data, $ids[$key]);
            }
        }

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }


    /**
     * 新增/编辑  文章
     */
    public function setEditArticle()
    {

        $ref = urlencode(I('post.ref', '/set/article'));

        //已登录用户
        $loginArr = $this->_Guser;

        //未登录
        if (!$loginArr) {
            echo "<script>parent.nowLogin();</script>";
            exit();
        }

        //:: 增加该用户是否有发布权限等

        //新增 or 编辑
        $ac = I('post.ac', 'add');

        // 文章类
            
        $title = strip_tags(I('post.title'));
        $category = intval(I('post.category'));
        $content = htmlspecialchars_decode(I('post.content'));
        $tags_arr = I('post.tags_arr', '');
        $coverImg = I('post.coverImg', '');

        $err = 0;
        if (!$title) {
            echo "<script>parent.showPopError('title', '请输入标题')</script>";
            $err = 1;
        }
        if (!$content) {
            //DO NOT HAVE POP ERROR SHOW TYPE
            echo "<script>parent.errorMsg('请输入内容')</script>";
            $err = 1;
        }


        //报错
        if ($err) {
            exit();
        }

        //tags 处理
        $tags = '';
        if ($tags_arr) {
            //去除重复TAGS
            $tags_arr = array_unique($tags_arr);
            //tags array to ,
            foreach ($tags_arr as $k=>$value){
                $tags .= $value.",";
            }
        }

        //数据写入
        $data = array(
            'cat_id'      => $category,
            'title'       => $title,
            'content'     => $content,
            'tags'        => $tags,
            'cover_img'   => $coverImg
        );

        //文章内容表
        $articleDb = D('Home/CmsArticle');

        //新增内容
        if ($ac == 'add') {
            $data['created_uid'] = $loginArr['uid'];
            $data['created_at'] = getTime();

            $res = $articleDb->set($data);
        }

        //更新内容
        if ($ac == 'edit') {
            
            $aid = intval(I('post.aid'));
            $res = $articleDb->setUpdate($data, $aid);
        }



        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }

    /**
     * 文章 批量操作
     * @param string $value [description]
     */
    public function setBatchArticle()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }

        $ref = urlencode(I('ref', '/set'));

        //所有id
        $ids = I('ids');

        //操作
        $type = I('type');

        //IDS TO ARRAY
        //$idArr = explode(',', $ids);

        $articleDb = D('Home/CmsArticle');
        //删除操作
        if ($type == 'del') {
            $data = array('app' => 2);
        }

        //审核
        if ($type == 'audit') {
            $data = array('app' => 3);
        }

        //恢复
        if ($type == 're') {
            $data = array('app' => 1);
        }

        // print_r($idArr);
        // exit();
        //操作
        $res = $articleDb->setBatchUpdate($data, $ids);

        //操作成功
        if ($res !== FALSE) {
            $data['status'] = 'success';
        }else{
            $data = array(
                'status'    => 'error',
                'msg'       => '内部错误, 请刷新后重试'.$res,
            );
        }
        $this->ajaxReturn($data);
    }

    /**
     * 上传封面图片
     * @return [type] [description]
     */
    public function uploadImg()
    {
        $coverImg = $_FILES['cover_img'];

        if (!$coverImg) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('请选择一个图片文件')</script>";
            exit();
        }
        if ($coverImg['type'] != 'image/jpeg') {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('必须为 JPEG 格式图片')</script>";
            exit();
        }
        if ($coverImg['size'] > (1024 * 1000 * 10)) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('请上传小于 10MB 的图片文件')</script>";
            exit();
        }

        $now = getTime();
        $newName = $now.rand("1000", "9999").'.jpg';
        //图片存储位置
        $newDir = date(Y)."/".date(m)."/".date(d)."/";
        $pathDir = getcwd()."/uploads/img/".$newDir;
        //创建文件夹
        creatDir($pathDir);
        //图片路径
        $newImg = $pathDir.$newName;

        //开始上传
        if (!move_uploaded_file($coverImg['tmp_name'], $newImg)) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('上传失败, 请刷新后重试')</script>";
            exit();
        }else{
            //显示 图片进一步处理
            $url = "/set/dialog/articleUpImg?imgpath=".$this->_Gset['IMG_URL'].$newDir.$newName;
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

        $pimg = I('post.img');
        $x = intval(I('post.x', 0));
        $y = intval(I('post.y', 0));
        $w = intval(I('post.w'));
        $h = intval(I('post.h'));
        $pwidth = intval(I('post.pwidth'));
        $pheight = intval(I('post.pheight'));

        $img = getcwd().$pimg;
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

        //按区域剪裁
        if ($w && $h) {

            $targ_w = $targ_h = 150; //保存的图片的大小
            $jpeg_quality = 100;


            $img_r = imagecreatefromjpeg($img);
            $dst_r = imagecreatetruecolor($targ_w, $targ_h);
            //切图
            imagecopyresampled($dst_r, $img_r, 0, 0, $x * $wp, $y * $hp,
                            $targ_w, $targ_h, $w * $wp, $h * $hp);

            //存图
            $setLogic->saveImg($img, $dst_r, $img);

            //有需要在进行缩放

            //销毁图像
            imagedestroy($dst_r);
        }else{
            //如果没有划区域
            $setLogic->cutphoto($img, $img, 150, 150);
        }

        // #### 处理完毕 需要设置OSS 在这里上传 ####
        // // OSS设置
        // $ossDb = D('Service/ServiceOss');
        // $ossArr = $ossDb->get();
        // //OSS 上传
        // $oss_sdk_service = ossService();
        // //BUCKET NAME
        // $bucket = "ripppple-avatar";
        // $oss_sdk_service->create_object_dir($ossArr['avatar_bucket'], $oss_path);
        // //SMALL
        // $response = $oss_sdk_service->upload_file_by_file($bucket, $oss_path.$getfile, $realFile);



        //操作成功
        echo "<script>parent.successCut('".$pimg."');</script>";
        exit();
    }




    /**
     * 评论管理 批量操作
     * @param string $value [description]
     */
    public function setBatchComment()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }

        $ref = urlencode(I('ref', '/set'));

        //所有id
        $ids = I('ids');

        //操作
        $type = I('type');

        //IDS TO ARRAY
        //$idArr = explode(',', $ids);

        $commentDb = D('Home/CmsComment');
        //删除操作
        if ($type == 'del') {
            $data = array('app' => 2);
        }

        //恢复
        if ($type == 're') {
            $data = array('app' => 1);
        }

        // print_r($idArr);
        // exit();
        //操作
        $res = $commentDb->setBatchUpdate($data, $ids);

        //操作成功
        if ($res !== FALSE) {
            $data['status'] = 'success';
        }else{
            $data = array(
                'status'    => 'error',
                'msg'       => '内部错误, 请刷新后重试'.$res,
            );
        }
        $this->ajaxReturn($data);
    }


    /**
     * 上传滚动图片
     * @return [type] [description]
     */
    public function uploadSlide()
    {
        $coverImg = $_FILES['cover_img'];

        if (!$coverImg) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('请选择一个图片文件')</script>";
            exit();
        }
        if ($coverImg['type'] != 'image/jpeg') {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('必须为 JPEG 格式图片')</script>";
            exit();
        }
        if ($coverImg['size'] > (1024 * 1000 * 10)) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('请上传小于 10MB 的图片文件')</script>";
            exit();
        }

        $setLogic = D('Set/Setservice', 'Logic');
        //图片存储路径
        $now = getTime();
        $newName = $now.rand("1000", "9999").'.jpg';
        //图片存储位置
        $newDir = date(Y)."/".date(m)."/".date(d)."/";
        $pathDir = getcwd()."/uploads/img/".$newDir;
        //创建文件夹
        creatDir($pathDir);
        //图片路径
        $newImg = $pathDir.$newName;

        //开始上传
        if (!move_uploaded_file($coverImg['tmp_name'], $newImg)) {
            echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
            echo "<script>parent.errorMsg('上传失败, 请刷新后重试')</script>";
            exit();
        }else{
            //保存图片
            $setLogic->cutphoto($newImg, $newImg, 1200, 250);

            //写入数据
            $slideDb = D('Service/ServiceSlide');
            $data = array(
                'img_url' => $this->_Gset['IMG_URL'].$newDir.$newName,
            );
            $slideDb->set($data);

            echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"/set/slide\");</script>";
            exit();
        }
        
    }



    /**
     * 批量编辑  首页滚动图片 设置
     */
    public function setEditAllSlide()
    {
        $ref = urlencode(I('post.ref', '/set'));

        $links = I('post.link');
        $targets = I('post.target');
        $sorts = I('post.sort');
        $dels = I('post.del');
        $ids = I('post.id');


        //SLIDE DB ...
        $slideDb = D('Service/ServiceSlide');


        //首先执行删除操作
        if ($dels) {
            //数组转换
            foreach ($dels as $key => $value) {
                $delIds .= $value.',';
            }

            $slideDb->del($delIds);
        }else{
            //没有删除操作 则更新 防止 已删除的 在更新ERROR

            //执行批量更新
            foreach ($ids as $key => $value) {
                $data = array(
                    'link'   => $links[$ids[$key]],
                    'target' => $targets[$ids[$key]],
                    'sort'   => $sorts[$ids[$key]],
                );
                $slideDb->setUpdate($data, $ids[$key]);
            }
        }

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }



    /**
     * 设置 OSS
     */
    public function setOss()
    {
        $ref = urlencode(I('post.ref', '/set'));

        $oss_open = I('post.oss_open');
        $oss_id = trim(I('post.oss_id', ''));
        $oss_key = trim(I('post.oss_key', ''));
        $article_bucket = trim(I('post.article_bucket', ''));
        $avatar_bucket = trim(I('post.avatar_bucket', ''));

        //设置为开启 需要填写完整
        if ($oss_open == 1) {
            $err = 0;
            if (!$oss_id) {
                echo "<script>parent.showPopError('oss_id', '请输入 OSS_ACCESS_ID')</script>";
                $err = 1;
            }
            if (!$oss_key) {
                echo "<script>parent.showPopError('oss_key', '请输入 OSS_ACCESS_KEY')</script>";
                $err = 1;
            }
            if (!$article_bucket) {
                echo "<script>parent.showPopError('article_bucket', '请输入 文章缩略图 bucket名称')</script>";
                $err = 1;
            }
            if (!$avatar_bucket) {
                echo "<script>parent.showPopError('avatar_bucket', '请输入 头像 bucket名称')</script>";
                $err = 1;
            }

            //报错
            if ($err) {
                exit();
            }
        }

        // 储存
        $ossDb = D('Service/ServiceOss');

        $data = array(
            'oss_open'       => $oss_open,
            'oss_id'         => $oss_id,
            'oss_key'        => $oss_key,
            'article_bucket' => $article_bucket,
            'avatar_bucket'  => $avatar_bucket,
        );
        $ossDb->setUpdate($data);

        echo "<script type=\"text/javascript\">parent.successMsg(\"操作成功, 正在跳转...\", \"".$ref."\");</script>";
        exit();
    }
}