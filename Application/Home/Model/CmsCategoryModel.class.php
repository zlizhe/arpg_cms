<?php
namespace Home\Model;
use Think\Model;

/**
 *  CMS 分类表
 */
class CmsCategoryModel extends Model{

	/**
	 * 删除分类
	 * @param  [type] $gid [description]
	 * @return [type]      [description]
	 */
	public function del($nids)
	{
		$id = $this->delete($nids);
		//删除 缓存 
		F('getCatList', NULL);
		return $id;
	}

	/**
	 * 取所有分类
	 * @return [type] [description]
	 */
	public function getCatList()
	{

		$results = F('getCatList');

		if (!$results) {
			$results = $this->field(array('id','value','upid','sort','app','path_name'))
					->order(array('sort'=>'ASC'))
					->select();

			// //分类树
			$results = formartTree($results);

			//写入缓存
			F('getCatList', $results);
		}
		return $results;
	}


	/**
	 * 通过ID 查看 分类详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get($id)
	{
		$row = $this->field(array('id','value','upid','sort','app','path_name'))->find($id);
		return $row;
	}

	/**
	 * 创建新分类
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);
		//删除 缓存 
		F('getCatList', NULL);
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
		//删除 缓存 
		F('getCatList', NULL);
		return $id;
	}

	/**
	 * 通过PATH 取分类信息
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	public function getByPath($path)
	{
		$this->field(array('id','path_name','value','app'));

		$map['path_name'] = $path;
		$row = $this->where($map)->select();

		//print_r($row);
		return $row;
		// //print_r($row);
		// if ($row) {
		// 	return true;
		// }else{
		// 	return false;
		// }
	}

	/**
	 * 取可用分类组
	 * @return [type] [description]
	 */
	public function getByUseful()
	{
		$results = $this->field(array('id','value','upid','sort','app','path_name'))
				->where('app = 1')
				->order(array('sort'=>'ASC'))
				->select();

		$articleDb = D('Home/CmsArticle');
		// 分类中的文章S
		foreach ($results as $key => $value) {
			$results[$key]['articles'] = $articleDb->getListByCat($value['id'], 8);
		}

		return $results;
	}


}