<?php
namespace Service\Model;
use Think\Model;

/**
 *  网站导航数组
 */
class ServiceNavModel extends Model{

	/**
	 * 删除导航
	 * @param  [type] $gid [description]
	 * @return [type]      [description]
	 */
	public function del($nids)
	{
		$id = $this->delete($nids);
		//删除 缓存 
		F('getNavList', NULL);
		return $id;
	}

	/**
	 * 只取一级导航 一次
	 * @return [type] [description]
	 */
	public function getNavOne()
	{
		$results = $this->field(array('id','value','link','upid','app','sort','target'))
				->where('level = 0')
				->order(array('sort'=>'ASC'))
				->select();

		return $results;
	}

	/**
	 * 取导航所有列表
	 * @return [type] [description]
	 */
	public function getNavList()
	{
		$results = F('getNavList');

		if (!$results) {
			$results = $this->field(array('id','value','link','upid','app','sort','target'))
					->order(array('sort'=>'ASC'))
					->select();
			// //分类树
			$results = formartTree($results);
			//写入缓存
			F('getNavList', $results);
		}

		return $results;
	}

	/**
	 * 前台取可用 导航列表 第一层导航
	 * @return [type] [description]
	 */
	public function getNavZero($actionName)
	{


		$results = $this->field(array('id','value','link','upid','app','sort','target','title','level'))
				->where('app = 1 AND level = 0')
				->order(array('sort'=>'ASC'))
				->cache(true, 60)
				->select();

		//有可能是当前LINK的几种情况
		$selectLink = array($actionName, '/'.$actionName, '/'.$actionName.'/');
		//print_r($selectLink);
		//如果是当前导航的LINK 则选中
		foreach ($results as $key => $value) {
			//选中LINK
			//echo $value['link'];
			if (in_array($value['link'], $selectLink)) {
				$results[$key]['select'] = 1;
			}else{
				$results[$key]['select'] = 0;
			}
			//子菜单
			$results[$key]['childArr'] = $this->getChild($value['id']);
		}

		// echo "<pre>";
		// print_r($results);
		// echo "</pre>";
		return $results;
	}

	/**
	 * 取子菜单(2级导航)
	 * @return [type] [description]
	 */
	public function getChild($id)
	{


		$this->field(array('id','value','link','upid','app','sort','target','title','level'))
			->where('app = 1 AND level = 1 AND upid = %d', array($id));

		$results = $this->select();


		return $results;
	}

	/**
	 * 通过ID 查看 导航详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get($id)
	{
		$row = $this->field(array('id','value','link','upid','app','sort','target','title','level'))->find($id);
		return $row;
	}

	/**
	 * 创建新导航
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);
		//删除 缓存 
		F('getNavList', NULL);
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
		F('getNavList', NULL);
		return $id;
	}
}