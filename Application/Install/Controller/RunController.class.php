<?php
namespace Install\Controller;
/**
 * User: lizhe
 * Date: 15/11/14
 * Time: 10:51
 */
class RunController {

    /**
     * 第一步
     * 检查环境
     * 是否有文件夹没有写权限
     * 扩展没有支持
     * PHP版本是否支持namespace
     */
    public function step1(){


        $path = getcwd().'/';
        $runtimePath = $path.'../Application/Runtime/';
        $configPath = $path.'../Application/Common/Conf/';
        $templatePath = $path.'Template/';
        $pluginPath = $path.'plugin/';
        $uploadsPath = $path.'uploads/';

        $error = false;
        if ($this->isWrite($runtimePath) == false){
            echo $runtimePath.'目录不可写'."<br/>";
            $error = true;
        }

        if ($this->isWrite($configPath) == false){

            echo $configPath.'目录不可写<br/>';
            $error = true;
        }

        if ($this->isWrite($templatePath) == false){

            echo $templatePath.'目录不可写<br/>';
            $error = true;
        }

        if ($this->isWrite($pluginPath) == false){

            echo $pluginPath.'目录不可写<br/>';
            $error = true;
        }

        if ($this->isWrite($uploadsPath) == false){

            echo $uploadsPath.'目录不可写<br/>';
            $error = true;
        }

        //权限 OK
        if ($error == false){
            file_put_contents(CONF_PATH.'install.lock', 'step2');
            redirect(U('Install/Install/step2'));
        }

        echo "Linux 系统请通过 sudo chmod -R 777 {DIRNAME} 来让文件夹有可写权限!<br/>修改完成后请刷新本页即可继续安装";
        exit;
    }

    function isWrite($path){
        $test_file = $path.'test';
        $fp = @fopen($test_file, 'wb');
        if ($fp === false)
        {
            return false; //如果目录中的文件创建失败，返回不可写。
        }
        //        if (@fwrite($fp, 'directory access testing.') !== false) {
        //            return true; //目录可写可读011，目录可写不可读 010
        //        }
        @fclose($fp);
        @unlink($test_file);
        return true;
    }
}