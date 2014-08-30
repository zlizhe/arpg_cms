<?php
//000000000000s:252:"SELECT t1.id,t1.content,t1.created_uid,t1.created_at,t1.created_ip,t1.app,t1.re_id,t1.aid,t2.realname FROM arpg_cms_comment AS t1 LEFT JOIN arpg_members_profile AS t2 ON t1.created_uid = t2.uid  WHERE t1.app = 1 ORDER BY t1.created_at DESC LIMIT 0,30  ";
?>