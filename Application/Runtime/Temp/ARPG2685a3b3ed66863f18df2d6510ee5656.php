<?php
//000000000000s:291:"SELECT t1.id,t1.cat_id,t1.title,t1.content,t1.app,t1.created_uid,t1.created_at,t1.comment_num,t1.view_num,t1.tags,t2.realname,t1.cover_img FROM arpg_cms_article AS t1 LEFT JOIN arpg_members_profile AS t2 ON t1.created_uid = t2.uid  WHERE t1.app = '3' ORDER BY t1.created_at DESC LIMIT 0,30  ";
?>