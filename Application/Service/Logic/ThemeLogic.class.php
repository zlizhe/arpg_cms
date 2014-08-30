<?php
namespace Service\Logic;

/**
 * 	主题 相关逻辑操作
 */
class ThemeLogic{

    /**
     * 创建默认模板文件
     * @return [type] [description]
     */
    public function creatDefault($dir)
    {
        //创建文件夹
        $isCreate = creatDir($dir);

        if ($isCreate) {
            $cssDir = $dir.'/Common';
            creatDir($cssDir);
            //创建默认CSS文件
            //extend.css
            $cssContent = "/* ARPG CMS 自定义主题 CSS样式扩展 */";
            $newCss = fopen($cssDir.'/extend.css', 'w');
            fwrite($newCss, $cssContent);
            fclose($newCss);
        }else{
            $msg = '您的目录没有写入权限, 请自行于 /Public/Template/ 创建 '.$dir.' 文件夹';
            echo "<script>parent.alert('".$msg."')</script>";
        }

        return true;
    }

    /**
     * 取主题的 预览图片 没有设置使用默认图片
     * @param  [type] $path_name [description]
     * @return [type]            [description]
     */
    public function getPreview($path_name)
    {
        $file = '/'.C('VIEW_PATH').$path_name.'/preview.gif';
        $preFile = getcwd().$file;
        //自定义文件存在 使用
        if (file_exists($preFile)) {
            return $file;
        }else{
            //使用默认预览图片
            return "/img/themes_preview.gif";
        }
    }

    /**
     * 删除主题文件夹
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function rmThemesFolder($path_name)
    {
        $file = '/'.C('VIEW_PATH').$path_name;
        getRmCache($file);
        return true;
    }
}