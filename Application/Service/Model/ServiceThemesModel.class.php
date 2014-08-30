<?php
namespace Service\Model;
use Think\Model;

/**
 *  网站主题 表
 */
class ServiceThemesModel extends Model{

	/**
	 * 删除主题
	 * @param  [type] $gid [description]
	 * @return [type]      [description]
	 */
	public function del($ids)
	{
		$id = $this->delete($ids);
		//删除 缓存 
		F('getThemesList', NULL);
		return $id;
	}

	/**
	 * 所有主题列表
	 * @return [type] [description]
	 */
	public function getThemesList()
	{
		$results = F('getThemesList');

		if (!$results) {
			$results = $this->field(array('id','name','path_name'))
					->order(array('id'=>'ASC'))
					//->cache('getThemesList')
					->select();

			$themesLogic = D('Service/Theme', 'Logic');
			//写入预览图片路径
			foreach ($results as $key => $value) {
				$results[$key]['preview'] = $themesLogic->getPreview($value['path_name']);
			}
			//写入缓存 
			F('getThemesList', $results);
		}

		return $results;
	}

	/**
	 * 通过 path_name 查询 相同
	 * @param  [type] $name path_name
	 * @return [type]       [description]
	 */
	public function getByPath($path)
	{
		
		$row = $this->where("path_name = '%s'", $path)->find();
		return $row;
	}


	/**
	 * 通过ID 查看 主题详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get($id)
	{
		$row = $this->field(array('name','path_name'))->cache(true)->find($id);
		return $row;
	}

	/**
	 * 创建新主题
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);
		F('getThemesList', NULL);
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
		F('getThemesList', NULL);
		
		return $id;
	}
}