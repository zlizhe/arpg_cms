<?php
namespace Member\Model;
use Think\Model;

/**
* 	会员组 相关	
*/
class MembersGroupModel extends Model
{
	
	
	/**
	 * 取所有 会员组
	 * @return [type] [description]
	 */
	public function getGroupList()
	{
		$results = $this->field(array('id','value','note'))->order('id')->select();
		return $results;
	}


	/**
	 * GET BY ID
	 * @return [type] [description]
	 */
	public function get($id)
	{
		$row = $this->field(array('id','value','note','in_actions'))->find($id);

		$row['in_actions'] = unserialize($row['in_actions']);
		return $row;
	}

	/**
	 * 创建新角色
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		try {
			$id = $this->add($data);
		} catch (Exception $e) {
			echo $e;
			exit();
		}

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
	 * 删除组
	 * @param  [type] $gid ID
	 * @return [type]      BOOL
	 */
	public function del($gid)
	{
		$id = $this->delete($gid);
		return $id;
	}

}