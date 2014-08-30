<?php
namespace Service\Model;
use Think\Model;

/**
 *  首页滚动图片
 */
class ServiceSlideModel extends Model{

	/**
	 * 删除滚动图片
	 * @param  [type] $gid [description]
	 * @return [type]      [description]
	 */
	public function del($ids)
	{
		$id = $this->delete($ids);
		//删除 缓存 
		F('getSlideList', NULL);
		return $id;
	}


	/**
	 * 取导航所有列表
	 * @return [type] [description]
	 */
	public function getSlideList()
	{

		$results = F('getSlideList');

		if (!$results) {
			$results = $this->field(array('id', 'img_url', 'link', 'sort', 'target'))
					->order(array('sort'=>'ASC'))
					->select();
			//写入缓存
			F('getSlideList', $results);
		}


		return $results;
	}


	/**
	 * 添加新图片
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);
		//删除 缓存 
		F('getSlideList', NULL);
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
		F('getSlideList', NULL);
		return $id;
	}
}