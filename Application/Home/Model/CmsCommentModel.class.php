<?php
namespace Home\Model;
use Think\Model;

/**
 *  CMS 评论表
 */
class CmsCommentModel extends Model{

	/**
	 * 删除评论
	 * @param  [type] $gid [description]
	 * @return [type]      [description]
	 */
	public function del($nids)
	{
		$id = $this->delete($nids);
		return $id;
	}

	/**
	 * 所有评论列表 SET取
	 * @return [type] [description]
	 */
	public function getCommentBySet($page, $url, $aid=0, $created_uid=0, $app=1, $count=30)
	{
		$this->table('__CMS_COMMENT__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.created_uid = t2.uid')
				->field(array('t1.id','t1.content','t1.created_uid','t1.created_at','t1.created_ip','t1.app','t1.re_id','t1.aid','t2.realname'));
		
		//状态
		if ($app) {
			$map['t1.app'] = $app;
		}
		//按文章分类
		if ($aid) {
			$map['t1.aid'] = $aid;
		}
		//按评论者
		if ($created_uid) {
			$map['t1.created_uid'] = $created_uid;
		}

		$row = $this->where($map)->order('t1.created_at DESC')->page($page, $count)->select();
		
		//总数量
		$this->table('__CMS_COMMENT__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.created_uid = t2.uid');
		//总数
		$how = $this->where($map)->count();

		//总页数
		$howpage = ceil($how / $count);
		//分页
		$multipage = html_multi($page, $howpage, $url);

    
    	$results = array(
			'row'       => $row,
			'how'       => $how,
			'mulitpage' => $multipage,
    	);
		return $results;

	}

	/**
	 * 评论列表
	 * @return [type] [description]
	 */
	public function getCommentList($aid, $offset=0, $limit=5, $app=0)
	{
		$this->table('__CMS_COMMENT__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.created_uid = t2.uid')
				->field(array('t1.id','t1.content','t1.created_uid','t1.created_at','t1.created_ip','t1.app','t1.re_id','t1.aid','t2.realname'));

		//文章ID
		$map['t1.aid'] = $aid;
		//$map['t1.re_id'] = 0;

		//状态
		if ($app) {
			$map['t1.app'] = $app;
		}

		$results = $this->where($map)->order(array('t1.created_at'=>'DESC'))
						->limit($offset, $limit)
						->cache(true, 5)
						->select();


		foreach ($results as $key => $value) {
			//评论被系统审核
			if ($value['app'] == 3) {
				$results[$key]['content'] = "<small><font color='#ccc'>该评论因包含屏蔽字符, 已被系统自动审核。</font></small>";
			}
			//被删除 SPAM
			if ($value['app'] == 2) {
				$results[$key]['content'] = "<small><font color='#ccc'>该评论因违反相关规定, 已被管理员删除。</font></small>";
			}
		}
		// print_r($results);
		// exit();
		return $results;
	}


	/**
	 * 通过ID 查看 评论详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get($id)
	{
		$this->table('__CMS_COMMENT__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.created_uid = t2.uid')
				->field(array('t1.id','t1.content','t1.created_uid','t1.created_at','t1.created_ip','t1.app','t1.re_id','t1.aid','t2.realname'));

		$row = $this->cache(true, 60)->find($id);

		//评论被系统审核
		if ($row['app'] == 3) {
			$row['content'] = "<small><font color='#ccc'>该评论因包含屏蔽字符, 已被系统自动审核。</font></small>";
		}
		//被删除 SPAM
		if ($row['app'] == 2) {
			$row['content'] = "<small><font color='#ccc'>该评论因违反相关规定, 已被管理员删除。</font></small>";
		}

		return $row;
	}

	/**
	 * 创建新评论
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);

		return $id;
	}

	/**
	 * 更新数据
	 * @param [type] $data [description]
	 */
	public function setUpdate($data, $id)
	{
		$this->where('id = %d', array($id));
		try {
			$id = $this->save($data);
		} catch (Exception $e) {
			echo $e;
			exit();
		}
		// echo $id;
		// exit();
		
		return $id;
	}

	/**
	 * 批量更新数据
	 * @param [type] $data [description]
	 */
	public function setBatchUpdate($data, $ids)
	{
		$map['id'] = array('in', $ids);
		$this->where($map);

		$id = $this->save($data);
		
		return $id;
	}
	

}