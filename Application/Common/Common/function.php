<?php


    

//生成二维码
function getQrcode($chl, $type='google',$x ='230',$level='L',$margin='0'){
    /**
     * google api 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
     * @param string $chl 二维码包含的信息，可以是数字、字符、二进制信息、汉字。不能混合数据类型，数据必须经过UTF-8 URL-encoded.如果需要传递的信息超过2K个字节，请使用POST方式
     * @param int $widhtHeight 生成二维码的尺寸设置
     * @param string $EC_level 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
     *                 L-默认：可以识别已损失的7%的数据
     *                 M-可以识别已损失15%的数据
     *                 Q-可以识别已损失25%的数据
     *                 H-可以识别已损失30%的数据
     * @param int $margin 生成的二维码离图片边框的距离
     */
    if ($type == 'google') {
        $img = '<img alt="正在努力生成二维码图像" src="http://chart.apis.google.com/chart?chs='.$x.'x'.$x.'&cht=qr&chld='.$level.'|'.$margin.'&chl='.urlencode($chl).'" />';
    }

    //liantu.com
    if ($type == 'liantu') {
        $param = '&bg=fafafa&fg=333';
        $img = '<img alt="正在努力生成二维码图像" src="http://qr.liantu.com/api.php?text='.urlencode($chl).$param.'" />';
    }

    //kuaipai.cn
    if ($type == 'kuaipai') {
        $img = '<img alt="正在努力生成二维码图像" src="http://api.kuaipai.cn/qr?chs='.$x.'x'.$x.'&chl='.urlencode($chl).'" />';
    }
    return $img;
}

//截取中文 UFT8使用
function cutstr($string, $length, $dot = ' ...') {
    if(strlen($string) <= $length) {
        return $string;
    }

    $pre = chr(1);
    $end = chr(1);
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

    $strcut = '';


        $n = $tn = $noc = 0;
        while($n < strlen($string)) {

            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }

            if($noc >= $length) {
                break;
            }

        }
        if($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);



    $strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    $pos = strrpos($strcut, chr(1));
    if($pos !== false) {
        $strcut = substr($strcut,0,$pos);
    }
    return $strcut.$dot;
}

//检查是否为中文
function checkChinese($data){
    if (preg_match("/[^\x80-\xff]/",$data)) {
        return true;
    }
    return false;
}

//手机号码 11位验证
function checkPhoneNo($data){
    if(!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $data)){
        return false;
    }
    return true;
}

//创建文件夹
function creatDir($dir){
    if(!file_exists($dir)){
        $isdir = mkdir($dir, 0777, true);
        @chmod($dir, 511);//0777 八进制
    }
    return $isdir;
}

/**
 * 无限分类 树状格式化
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function formartTree($data)
{
    // $tree = array(); //格式化好的树
    // //ID 变下标
    // foreach ($data as $key => $value){
    //     $tree[$value['id']] = $value;
    // }


    // foreach ($tree as $key2 => $value2) {
    //     if ($value2['upid']) {
    //         //print_r($value);            
    //         $tree[$value2['upid']]['son'] = $value2;
    //         unset($tree[$value2['id']]);
    //     }
    // }
    $tree = new Org\Arpg\FormartTree();
    $tree->tree($data);
    $results = $tree->getArray();
    //print_r($results);
    return $results;
}



/**
 * //是否有权限访问
 * //$return 是否返回错误 页面
 * @param  [type]  $action action name
 * @param  boolean $return BOOL
 * @return boolean         BOOL
 */
function isPurview($action, $return=true, $alert=true) {
    //RETURN TRUE AJAX USE
    //RETURN FALSE PAGE USE
    //ACTION NAME全部转小写
    //$action = strtolower($action);

    $memberLogic = D('Member/Member', 'Logic');
    //是否已经登录
    $memberArr = $memberLogic->getIsLogin();

    //未登錄的先登錄
    if (!$memberArr) {
        $url = U('/member/login', array('ref'=>getRef()));
        echo "<script>window.open('".$url."', '_self'); </script>";
        exit();
    }

    //用户组权限
    $groupDb = D('Member/MembersGroup');
    $groupArr = $groupDb->get($memberArr['groupid']);

    // echo $action;
    // print_r($groupArr);
    //当前ACTION 是否在用户组内
    if ($groupArr['in_actions'] && in_array($action, $groupArr['in_actions'])){
        return true;
    }else{
        //跳转
        if ($return == false) {

            // 是否弹出 窗口
            if ($alert == true) {
                echo "<script>parent.$(\"#dialog\").modal('hide');</script>";
                echo "<script>parent.alert('您没有权限访问该页面');</script>";
                exit();
            }else{
                //无需显示错误页面
                return false;
            }
        }else{
            // == false DEFAULT SHOW ERROR PAGE
            echo '您没有权限访问该页面';
            exit();
            return false;
        }
    }


}

//BBCODE TO HTML
function bb2html($text){
   $bbcode = array(
      "/\[i\](.*?)\[\/i\]/is",
      "/\[b\](.*?)\[\/b\]/is",
      "/\[u\](.*?)\[\/u\]/is",
      "/\[img\](.*?)\[\/img\]/is",
      //"/\[url=(.*?)\](.*?)\[\/url\]/is",
      "/\[size=(.*?)\](.*?)\[\/size\]/is",
      "/\[size=(.*?)\](.*?)\[\/size\]/is",
      "/\[color=(.*?)\](.*?)\[\/color\]/is",
      "/\[color=(.*?)\](.*?)\[\/color\]/is",
      "/\[font=(.*?)\](.*?)\[\/font\]/is",
      //"/\\r\n/is",
   );
   $html = array(
      "<i>$1</i>",
      "<b>$1</b>",
      "<u>$1</u>",
      "<img src=\"$1\" />",
      //"<a href=\"".SITE_URL."/index/go?url=$1\" target=\"_blank\">$2</a>",
      '<font size=$1>$2</font>',
      '<font size=$1>$2</font>',
      '<font color=$1>$2</font>',
      '<font color=$1>$2</font>',
      '<font face=$1>$2</font>',
      //'<br />',
   );
   $newtext = nl2br(preg_replace($bbcode, $html, $text));
   return $newtext;
}


//时间 人性化处理
function humDate($date, $type='Y-m-d H:i')
{
    //return date($type, $date);
    //分钟
    $second = date('YmdHi', $date);
    $nsecond = date('YmdHi', getTime());
    //60分钟内
    $difSecond = $nsecond - $second;
    if ($difSecond < 2) {
        return "刚刚";
    }
    if ($difSecond < 60) {
        return $difSecond." 分钟前";
    }

    //小时
    $hour = date('YmdH', $date);
    $nhour = date('YmdH', getTime());
    //24小时内
    $difHour = $nhour - $hour;
    if ($difHour < 24) {
        return $difHour." 小时前";
    }

    //天
    $day = date('Ymd', $date);
    $nday = date('Ymd', getTime());
    //15天内
    $difDay = $nday - $day;
    if ($difDay < 15) {
        return $difDay." 天前";
    }

    //返回格式
    return date($type, $date);
    
}

/**
 * 通过IP取地区
 * @param  [type] $ip [description]
 * @return [type]     [description]
 */
function getLocation($getip)
{
    if ($ip == '127.0.0.1') {
        return '火星';
    }
    $ip = new Org\Net\IpLocation('../../../../Public/data/ipdata/UTFWry.dat'); // 实例化类 参数表示IP地址库文件
    $area = $ip->getlocation($getip); // 获取某个IP地址所在的位置
    //print_r($area);
    return $area['country'];
}

//多页码分页 
function html_multi($page, $howpage, $url="", $adjacents=4) {
    
    $repage    = "<li><a href=\"".$url.($page-1)."\">&laquo;</a></li>";
    $nxpage    = "<li><a href=\"".$url.($page+1)."\">下一页 &raquo;</a></li>";
    if ($page <= 1){
        $repage = "";
    }
    if ($page >= $howpage){
        $nxpage = "";
    }
    if ($page > ($adjacents + 1)) {
        $first = "<li><a href=\"".$url.'1'."\">1 ...</a></li>";
    }else{
        $first = '';
    }
    if ($page < ($howpage - $adjacents)){
        $last = "<li><a href=\"".$url.$howpage."\">... $howpage</a></li>";
    }else{
        $last = '';
    }

    $multipage = "";
    $multipage = $first;
    $multipage .= $repage;

    //页数过多时
    /*if (($howpage - $page) >= 10){
        $howpage = 10;
    }*/
    for ($i=1; $i<=$howpage; $i++){  //页码
        if ($page == $i){
            $multipage .= "<li class=\"active\"><a href=\"#\">$i</a></li>";
        }elseif($page > ($i + $adjacents) || $page < ($i - $adjacents)){

        }else{
            $multipage .= "<li><a href=\"".$url.$i."\">$i</a></li>";
        }
    }
    $multipage .= $last;
    $multipage .= $nxpage;

    return $multipage;
}

/**
 * 取网站配置
 * @return [type] [description]
 */
function getSiteSet()
{
    $siteSetting = new Common\Controller\PublicController();
    return $siteSetting->_getSiteSet();
}

/**
 * 发送邮件
 * @param  收件地址 $address [description]
 * @param  标题 $title   [description]
 * @param  内容 $content [description]
 * @param  发件人 $from    [description]
 * @return [type]          [description]
 */
function sendMail($address, $title, $content, $from=NULL, $fromName=Null)
{
    //网站配置
    $setArr = getSiteSet();

    //发件人为空时
    $from = $from ? : $setArr['MAIL_USERNAME'];
    $fromName = $fromName ? : $setArr['SITE_NAME'];

    $mail = new Org\Arpg\SendMail();
    //配置文件修改
    $mail->setServer($setArr['MAIL_SERVER'], $setArr['MAIL_USERNAME'], $setArr['MAIL_PASSWORD']);
    $mail->setFrom($from, $fromName);
    $mail->setReceiver($address);
    $mail->setMailInfo($title, $content);
    $mail->sendMail();
    return true;
}



//取 当前时间
function getTime()
{
    return time();
}


//取页面REF
function getRef() {
    
    return urlencode($_SERVER["REQUEST_URI"]);
}


//判断长度
/*
 * @$C_cahr 字符 
 * @$I_len1 长度一
 * @$I_len2 长度二
 * 
 * */ 
function checkLengthBetween($C_cahr, $I_len1, $I_len2=100)
{
    $C_cahr = trim($C_cahr);
    if (strlen($C_cahr) < $I_len1) {
        return false;
    }
    if (strlen($C_cahr) > $I_len2) {
        return false;
    }
    return true;
}
//判断 中文长度
function checkChineseLen($str)
{      
    $n = 0; $p = 0; $c = '';
    $len = strlen($str);
    for($i = 0; $i < $len; $i++) {
        $c = ord($str{$i});
        if($c > 252) {
            $p = 5;
        } elseif($c > 248) {
            $p = 4;
        } elseif($c > 240) {
            $p = 3;
        } elseif($c > 224) {
            $p = 2;
        } elseif($c > 192) {
            $p = 1;
        } else {
            $p = 0;
        }
        $i+=$p;$n++;
    }     
    return $n;
}

//特殊字符检验 
function checkSpecial($data){
    if (!preg_match("/^[_a-zA-Z0-9]*$/", $data)){
        return false;
    }
    return true;
}
//特殊字符检验 可以输入中文
function checkSpecialCh($data){
    if (!preg_match("/^[_a-zA-Z0-9\x7f-\xff]*$/", $data)){
        return false;
    }
    return true;
}

//判断 是否 为数字
function checkFloat($data){
    $data = strval($data);
    if (preg_match('/^0[^.]/', $data)){
        return false;
    }
    if (preg_match('/^\d+(\.\d+)?$/', $data)){
        return true;
    }else{
        return false;
    }
}

//检查邮件是否合法
function checkEmailAddr($data){
    if (!preg_match("/^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$/",$data)){
        return false;
    }
    return true;
}



/**
 * / 检测输入的验证码是否正确，$code为用户输入的验证码字符串
 * @param  [type] $code [description]
 * @param  string $id   [description]
 * @return [type]       [description]
 */
function checkVerify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}


/**
 * 创建验证字符
 * @return 验证字符 [description]
 */
function getVerify($length=5, $fontSize=32.9, $useNoise=true, $useCurve=true, $useZh=false, $useImgBg=false){

	$config =    array(
	    'fontSize'    =>    $fontSize,    // 验证码字体大小
	    'length'      =>    $length,     // 验证码位数
	    'useNoise'    =>    $useNoise, // 验证码杂点
        // 验证码字体使用 ThinkPHP/Library/Think/Verify/ttfs/5.ttf
        'useZh'     =>  $useZh,           // 使用中文验证码 
        // 开启验证码背景图片功能 随机使用 ThinkPHP/Library/Think/Verify/bgs 目录下面的图片
        'useImgBg'  =>  $useImgBg,           // 使用背景图片 
        'useCurve'  =>  $useCurve,            // 是否画混淆曲线
        'imageH'    =>  0,               // 验证码图片高度
        'imageW'    =>  270,               // 验证码图片宽度
        'fontttf'   =>  '',              // 验证码字体，不设置随机获取
        'bg'        =>  array(255,255,255),  // 背景颜色
	);

	$Verify = new \Think\Verify($config);
    //ob_clean();
	return $Verify->entry();

}



//清空缓存文件
function getRmCache($dirname='', $root='') {
    //文件夹
    if (!$dirname && $root) {
        $cache = getcwd().'/../Application/'.$root;
        $dirname = $cache;
    }
    
    if(file_exists($dirname)){//首先判断目录是否有效  
        $dir = opendir($dirname);//用opendir打开目录  
        while($filename = readdir($dir)){//使用readdir循环读取目录里的内容  
            if($filename != "." && $filename != ".."){//排除"."和".."这两个特殊的目录  
                $file = $dirname."/".$filename;  
                if(is_dir($file)){//判断是否是目录，如果是则调用自身  
                    getRmCache($file); //使用递归删除子目录    
                }else{  
                    @unlink($file);//删除文件  
                }  
            }  
        }  
        closedir($dir);//关闭文件操作句柄  
        $isrm = rmdir($dirname);//删除目录   
    }

    //结束了
    if ($dirname == $cache) {
        return true;
    }else{
        return false;
    }

    // //是否成功
    // if ($isrm) {
    //     return true;
    // }else{
    //     return false;
    // }
}