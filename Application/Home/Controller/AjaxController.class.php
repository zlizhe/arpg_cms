<?php
namespace Home\Controller;
use Common\Controller\PublicController;

/**
 *  CMS AJAX
 */
class AjaxController extends PublicController {


    /**
     * 前置操作
     * @return [type] [description]
     */
    public function _initialize()
    {
        //执行开始
        $this->_run();
    }

    /**
     * 添加 评论内容
     */
    public function setComment()
    {

        $op = I('get.op');

        $comment = strip_tags(trim(I('post.comment')));
        $aid = intval(I('post.aid'));
        $re_id = intval(I('post.re_id', 0));

        //未登录
        $userArr = $this->_Guser;
        if (!$userArr) {
            echo "<script>parent.nowLogin();</script>";
            exit();
        }


        if (!$comment){
            $target = $op.'_text';
            echo "<script>parent.showCommentMsg('error', '".$target."', '请输入评论内容')</script>";
            exit();
        }

        //COMMENT DB
        $commentDb = D('Home/CmsComment');

        //评论数组
        $data = array(
            'content'     => $comment,
            'created_uid' => $userArr['uid'],
            'created_at'  => getTime(),
            'created_ip'  => get_client_ip(),
            'aid'         => $aid,
            're_id'       => $re_id,
        );
        $commentDb->set($data);


        //文章表 评论数增加
        $contentLogic = D('Home/Content', 'Logic');
        $contentLogic->plusOne($aid, 'comment_num');

        echo "<script>parent.showCommentMsg('success', 'publish_text','评论成功')</script>";
        exit();
    }

    /**
     * 文章内各种+1 浏览数+1 评论数+1 
     * @param [type] $aid  [description]
     * @param string $type [description]
     */
    public function setPlus()
    {
        //没有参数取值
        $aid = I('aid');
        $type = I('type');

        $contentLogic = D('Home/Content', 'Logic');
        $contentLogic->plusOne($aid, $type);
        return true;
    }

    /**
     * 取评论内容
     * @return [type] [description]
     */
    public function getComment()
    {
        //非 AJAX 提交
        if (!IS_AJAX) {
            exit();
        }


        $aid = I('aid');
        $offset = intval(I('offset', 0));

        //评论表
        $commentDb = D('Home/CmsComment');
        $commentArr = $commentDb->getCommentList($aid, $offset);


        //内容
        $res = '';
        foreach ($commentArr as $key => $value) {

            $userAvatar = $this->_Gset['IMG_AVATAR'].$value['created_uid']."/".$value['created_uid']."_small.jpg";
            $commentAt = humDate($value['created_at']);
            $commentArea = getLocation($value['created_ip']);

            //是回复内容
            if ($value['re_id'] != 0) {
                $replayArr = $commentDb->get($value['re_id']);

                $replayAvatar = $this->_Gset['IMG_AVATAR'].$replayArr['created_uid']."/".$replayArr['created_uid']."_small.jpg";
                $replayAt = humDate($replayArr['created_at']);
                $replayArea = getLocation($replayArr['created_ip']);

                $replayHtml = "
                    <div class=\"media replay_area\">
                        <a class=\"pull-left\" href=\"/space/$replayArr[created_uid]\" target=\"_blank\">
                            <img class=\"media-object img-rounded\" src=\"$replayAvatar\" />
                        </a>
                        <div class=\"media-body\">
                            <h5 class=\"media-heading\">
                                <a href=\"/space/$replayArr[created_uid]\" target=\"_blank\">$replayArr[realname]</a>
                                <small> · $replayAt [ $replayArea ]</small>
                            </h5>
                            <p>$replayArr[content]</p>
                        </div>
                    </div>
                ";
            }else{
                $replayHtml = '';
            }

            //是否有删除权限 
            if ($this->_Guser) {
                if (isPurview('Set_Ajax_setBatchComment', false, false)) {
                    $isDel = "
                        <a href=\"javascript:;\" onclick=\"arpgBatch('确定要删除该评论', 'arpg_check', 'setBatchComment', 'del', $value[id]);\"><span class=\"glyphicon glyphicon-trash\"></span></a>
                        &nbsp;&nbsp;
                    ";
                }else{
                    $isDel = '';
                }
            }


            $res .= "
                <li class=\"media\" id=\"replay_$value[id]\">
                    <a class=\"pull-left\" href=\"/space/$value[created_uid]\" target=\"_blank\">
                        <img class=\"media-object img-rounded\" src=\"$userAvatar\" />
                    </a>
                    <div class=\"media-body\">
                        <h5 class=\"media-heading\">
                            <a href=\"/space/$value[created_uid]\" target=\"_blank\">$value[realname]</a>
                            <small> · $commentAt [ $commentArea ]</small>
                        </h5>
                        $replayHtml
                        <p>$value[content]</p>
                        
                    </div>
                    <div class=\"pull-right\">
                        <small>
                            <a href=\"javascript:;\" onclick=\"replay($value[id]);\"><span class=\"glyphicon glyphicon-comment\"></span> 回复</a>
                            &nbsp;&nbsp;
                            $isDel
                        </small>
                    </div>
                </li>
            ";
        }

        //输出内容
        if ($res) {
            $data = array(
                'status' => 'success',
                'res'    => $res,
            );
        }else{
            $data['status'] = 'error';
        }

        $this->ajaxReturn($data);

    }
}