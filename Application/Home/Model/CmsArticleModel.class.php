<?php
namespace Home\Model;
use Think\Model;

/**
 *  CMS 内容文章
 */
class CmsArticleModel extends Model{

	/**
	 * 删除文章
	 * @param  [type] $gid [description]
	 * @return [type]      [description]
	 */
	public function del($nids)
	{
		$id = $this->delete($nids);
		return $id;
	}

	public function getArticleList($page, $url, $cat_id=0, $created_uid=0, $app=1, $query='', $count=30)
	{	

		$this->table('__CMS_ARTICLE__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.created_uid = t2.uid')
				->field(array('t1.id','t1.cat_id','t1.title','t1.content','t1.app','t1.created_uid','t1.created_at','t1.comment_num','t1.view_num','t1.tags','t2.realname','t1.cover_img'));

		//echo $sql;
		$map['t1.app'] = $app;

		//通过分类筛选
		if ($cat_id) {
			$map['t1.cat_id'] = $cat_id;
		}

		//通过发布者筛选
		if ($created_uid) {
			$map['t1.created_uid'] = $created_uid;
		}

		//搜索文章
		if ($query) {
			$where['t1.id'] = array('LIKE', "%$query%");
			$where['t1.title'] = array('LIKE', "%$query%");
			$where['t1.content'] = array('LIKE', "%$query%");
			$where['t1.tags'] = array('LIKE', "%$query%");
			$where['t2.realname'] = array('LIKE', "%$query%");
			$where['_logic'] = 'OR';
			$map['_complex'] = $where;
		}

		//数据
		$row = $this->where($map)->order('t1.created_at DESC')->page($page, $count)->cache(true, 60)->select();

		//分类name
		$categoryDb = D("Home/CmsCategory");
		foreach ($row as $key => $value) {
			$catArr = $categoryDb->get($value['cat_id']);
			$row[$key]['cat_name'] = $catArr['value'];
			$row[$key]['summary'] = cutstr(strip_tags($value['content']), 280);
			if ($value['tags']) {
				//echo $value['tags'];
				$row[$key]['tags_arr'] = array_filter(explode(',', $value['tags']));
			}
			//print_r($row[$key]['tags_arr']);
		}

		//总数量
		$this->table('__CMS_ARTICLE__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.created_uid = t2.uid');
		//总数
		$how = $this->where($map)->cache(true, 60)->count();

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
	 * 通过ID 查看 文章详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get($id)
	{
		$row = $this->field(array('id','cat_id','title','content','app','created_uid','created_at','comment_num','view_num','tags','cover_img'))->cache(true, 60* 10)->find($id);
		return $row;
	}

	/**
	 * 取可用文章
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getByUseful($id)
	{
		$this->field(array('id','cat_id','title','content','app','created_uid','created_at','comment_num','view_num','tags','cover_img'));
		
		$map['app'] = 1;
		$map['id']	= $id;

		//查询
		$row = $this->where($map)->select();
		//print_r($row);
		return $row;
	}

	/**
	 * 创建新文章
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

	/**
	 * 数字 + 1
	 * @param [type] $op 项目
	 */
	public function setPlus($id, $op='view_num')
	{
		$id = $this->where('id = %d', array($id))->setInc($op);
		return $id;
	}

	/**
	 * 取分类可用文章
	 * @param  integer $count [description]
	 * @return [type]         [description]
	 */
	public function getListByCat($cat_id=0, $count=10)
	{
		$this->field(array('id','cat_id','title','content','app','created_uid','created_at','comment_num','view_num','tags','cover_img'));
		
		//可用
		$map['app'] = 1;
		if ($cat_id) {
			$map['cat_id'] = $cat_id;
		}

		$results = $this->where($map)->order('created_at DESC')->select();

		return $results;
	}

}