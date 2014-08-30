<?php
namespace Member\Model;
use Think\Model;

/**
* 	角色权限 相关	
*/
class MembersCompetenceModel extends Model
{
	
	/**
	 * 取所有 权限
	 * @return [type] [description]
	 */
	public function getComList()
	{
		$results = $this->field(array('id','value','action','upid'))
				//->where('upid = 0')
				->order(array('id'=>'ASC'))
				->select();

		// foreach ($results as $key => $value) {
		// 	if ($value['upid']) {
		// 		// 取子类
		// 		$results[$key]['son'] = $this->field(array('id','value','action','upid'))->where('id = %d', $value['upid'])->order(array('id'=>'DESC'))->select();
		// 	}
		// }
		// echo "<pre>";
		// //分类树
		$results = formartTree($results);
		// print_r($results);
		// echo "</pre>";
		return $results;
	}


	/**
	 * GET BY ID
	 * @return [type] [description]
	 */
	public function get($id)
	{
		$row = $this->find($id);
		return $row;
	}


	/**
	 * 创建新权限
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);

		return $id;
	}

	/**
	 * 更新权限数据
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
	 * 删除权限
	 * @param  [type] $cid ID
	 * @return [type]      BOOL
	 */
	public function del($cid)
	{
		$id = $this->delete($cid);
		return $id;
	}

}