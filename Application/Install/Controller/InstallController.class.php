<?php
namespace Install\Controller;
use Common\Controller\PublicController;

/**
 *  初始化安装 首页
 */
class InstallController extends PublicController{

    /**
     * SEO 设置相关 
     * @var array
     */
    private $_seoSetting = array();

    private $stepFile = '';
    private $stepContent = '';
    /**
     * 前置操作
     * @return [type] [description]
     */
    public function _initialize()
    {
        //标题
        $this->_seoSetting['siteName'] = "ARPG_CMS 安装程序";

        $this->stepFile = CONF_PATH.'install.lock';
        $this->stepContent = file_get_contents($this->stepFile);

        if ($this->stepContent == ''){
            redirect(U('Install/Run/step1'));
        }
        //指定LAYOUT
        layout(false);
    }

    /**
     * 配置数据库
     * 检查数据库连接
     * 创建库
     * 创建表
     * 创建表结构
     */
    public function step2(){
        if ($this->stepContent != __FUNCTION__){
           redirect(U('Install/Install/'.$this->stepContent));
        }

        if (IS_POST){
            $error = '';
            $host = I('host') ?: 'localhost';
            $this->assign('host', $host);
            $name = I('name') ?: 'arpg_cms';
            $this->assign('name', $name);
            $user = I('user') ?: 'root';
            $this->assign('user', $user);
            $password = I('password');
            $this->assign('password', $password);
            $port = I('port') ?: '3306';
            $this->assign('port', $port);
            $prefix = I('prefix') ?: 'arpg_';
            $this->assign('prefix', $prefix);



            //连接测试
            $dsn = "mysql:host={$host};port={$port}";
            try {

                $dbh = new \PDO($dsn, $user, $password);
            }catch (\PDOException $e){
                $error = '数据库连接失败请检查配置';
            }

            if (!$error){
                $dbName = $dbh->query("use {$name};");
                if ($dbName)
                    $error = "数据表 {$name} 已存在, 换个名称吧";
            }

            if (!$error){

                //创建新表 及数据结构
                $dbsql = file_get_contents(getcwd().'/../_db_bak/arpg_cms.sql');
                $dbCreate = $dbh->exec("CREATE DATABASE `{$name}`");
                $dbh->exec("use {$name}");
                $res = $dbh->exec($dbsql);
                if ($res !== 'false'){
                    echo "数据表创建完成";
                    //写入配置文件
                    $configFile = file_get_contents(CONF_PATH.'config.bak.php');
                    $configFile = str_replace("{DB_HOST}", $host, $configFile);
                    $configFile = str_replace("{DB_NAME}", $name, $configFile);
                    $configFile = str_replace("{DB_USER}", $user, $configFile);
                    $configFile = str_replace("{DB_PWD}", $password, $configFile);
                    $configFile = str_replace("{DB_PORT}", $port, $configFile);
                    $namespace = '';
                    for ($i = 0; $i < 8; $i++) {
                        $namespace .= chr(mt_rand(65, 90));
                    }
                    $configFile = str_replace("{NAMESPACE}", strtoupper($namespace), $configFile);

                    file_put_contents(CONF_PATH.'config.php', $configFile);
                    file_put_contents($this->stepFile, 'step3');
                    redirect(U('Install/Install/step3'));
                }else{
                    echo "数据表创建失败";
                    exit;
                }
            }
            $this->assign('error', $error);
        }

        $this->_seoSetting['title'] = '配置数据库';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }


    public function step3(){
        if ($this->stepContent != __FUNCTION__){
            redirect(U('Install/Install/'.$this->stepContent));
        }
        if (IS_POST){
            $site_name = I('site_name');
            $site_url = I('site_url');
            $mail_server = I('mail_server');
            $mail_username = I('mail_username');
            $mail_password = I('mail_password');
            $img_url = I('img_url');
            $avatar_url = I('avatar_url');
            $adminName = I('adminName');
            $adminPassword = I('adminPassword');

            $error = '';
            if (!$site_url)
                $error = '必须填写网站真实URL';

            if (!$adminName)
                $error = '管理员账号必须填写';

            if (!$adminPassword)
                $error = '管理员密码必须填写';

            $service = D('Service/ServiceSetting');
            $service->setUpdate(array(
                                    'site_name'     => $site_name,
                                    'site_url'      => $site_url,
                                    'mail_server'   => $mail_server,
                                    'mail_username' => $mail_username,
                                    'mail_password' => $mail_password,
                                    'img_url'       => $img_url,
                                    'img_avatar'    => $avatar_url
                                ));

            $member = D('Member/Members');
            $member->setUpdate(array(
                                   'username' => $adminName,
                                   'password' => md5($adminPassword)
                               ), 1);

            file_put_contents($this->stepFile, 'complete');
            //successful
            redirect(U('Install/Install/complete'));
            $this->assign('error', $error);

        }
        $this->_seoSetting['title'] = '少量配置';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }


    public function complete(){
        if ($this->stepContent != __FUNCTION__){
            redirect(U('Install/Install/'.$this->stepContent));
        }
        //lock
        //file_put_contents(CONF_PATH.'install.lock', 'Install Date '.date('Y-m-d: H:i:s'));

        $this->_seoSetting['title'] = '完成安装, 请尽情享用';
        $this->assign('seoSetting', $this->_seoSetting);
        $this->display();
    }
}