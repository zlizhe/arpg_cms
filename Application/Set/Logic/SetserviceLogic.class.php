<?php
namespace Set\Logic;

/**
 * 	后台相关的逻辑操作
 */
class SetserviceLogic{

    /**
     * 生成缩略图
     * @param  [type] $o_photo [description]
     * @param  [type] $d_photo [description]
     * @param  [type] $width   [description]
     * @param  [type] $height  [description]
     * @return [type]          [description]
     */
    public function cutphoto($o_photo,$d_photo,$width,$height){
    
    
    
        $temp_img = $this->imageCreateFromAny($o_photo);
        $o_width   = imagesx($temp_img);                                 //取得缩略图宽
        $o_height = imagesy($temp_img);
        
        //如果 不给高度，根据高宽比例出高度
        if (!$height) {
            //比例
            $px = $o_height / $o_width;
            $height = $width * $px;
        }

        //不给宽度
        if (!$width) {
            //比例
            $px = $o_width / $o_height;
            $width = $height * $px;
        }
    
        //判断处理方法
        if($width >$o_width || $height>$o_height){         //原图宽或高比规定的尺寸小,进行压缩
    
            $newwidth=$o_width;
            $newheight=$o_height;
    
            if($o_width>$width){
                $newwidth=$width;
                $newheight=$o_height*$width/$o_width;
            }
    
            if($newheight>$height){
                $newwidth=$newwidth*$height/$newheight;
                $newheight=$height;
            }
    
            //缩略图片
            $new_img = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $newwidth, $newheight, $o_width, $o_height);
            $this->saveImg($o_photo, $new_img , $d_photo);
            //imagejpeg($new_img , $d_photo);
            imagedestroy($new_img);
    
    
        }else{                                                                                 //原图宽与高都比规定尺寸大,进行压缩后裁剪
    
            if($o_height*$width/$o_width>$height){         //先确定width与规定相同,如果height比规定大,则ok
                $newwidth=$width;
                $newheight=$o_height*$width/$o_width;
                $x=0;
                $y=($newheight-$height)/2;
            }else{                                                                         //否则确定height与规定相同,width自适应
                $newwidth=$o_width*$height/$o_height;
                $newheight=$height;
                $x=($newwidth-$width)/2;
                $y=0;
            }
    
            //缩略图片
            $new_img = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $newwidth, $newheight, $o_width, $o_height);
            $this->saveImg($o_photo, $new_img , $d_photo);
            //imagejpeg($new_img , $d_photo);
            imagedestroy($new_img);
    
    
            $temp_img = $this->imageCreateFromAny($d_photo);
            $o_width   = imagesx($temp_img);                                 //取得缩略图宽
            $o_height = imagesy($temp_img);
    
    
            //裁剪图片
            $new_imgx = imagecreatetruecolor($width,$height);
            imagecopyresampled($new_imgx,$temp_img,0,0,$x,$y,$width,$height,$width,$height);
            $this->saveImg($o_photo, $new_imgx , $d_photo);
            //imagejpeg($new_imgx , $d_photo);
            imagedestroy($new_imgx);
        }
    
    }

    /**
     * 保存图片
     * @param  [type] $o_photo [description]
     * @param  [type] $new_img [description]
     * @param  [type] $d_photo [description]
     * @return [type]          [description]
     */
    public function saveImg($o_photo, $new_img , $d_photo){


        $type = $this->imgType($o_photo);
        switch ($type) {
            case "image/gif":
                imagegif($new_img , $d_photo);
                break;
            case "image/jpeg":
                imagejpeg($new_img , $d_photo, 100);
                break;
            case "image/png":
                imagepng($new_img , $d_photo);
                break;
            default:
                imagejpeg($new_img , $d_photo, 100);
                break;
        }

        return;
    }

    /**
     * /取得类型
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function imgType($file){
        $type = getimagesize($file);
        return $type['mime'];
    }

    /**
     * 创建图片
     * @param  [type] $filepath [description]
     * @return [type]           [description]
     */
    public function imageCreateFromAny($filepath) {
    
        $type = $this->imgType($filepath);
        switch ($type) {
            case "image/gif" :
                $temp_img = imageCreateFromGif($filepath);
                break;
            case "image/jpeg" :
                $temp_img = imageCreateFromJpeg($filepath);
                break;
            case "image/png" :
                $temp_img = imageCreateFromPng($filepath);
                break;
            default:
                $temp_img = imageCreateFromJpeg($filepath);
                break;
        }
        return $temp_img;
    }


    // /**
    //  * 创建文件夹 赋予权限 
    //  * return 新文件路径
    //  */
    // public function setImgPath()
    // {
        
    //     $now = getTime();
    //     $newName = $now.rand("1000", "9999").'.jpg';
    //     //图片存储位置
    //     $newDir = date(Y)."/".date(m)."/".date(d)."/";
    //     $pathDir = getcwd()."/uploads/img/".$newDir;
    //     //创建文件夹
    //     creatDir($pathDir);
    //     //图片路径
    //     $newImg = $pathDir.$newName;
    //     return $newImg;
    // }
}