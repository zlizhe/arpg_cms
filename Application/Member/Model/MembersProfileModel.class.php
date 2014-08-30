<?php
namespace Member\Model;
use Think\Model;

/**
 *  用户 数据表相关
 */
class MembersProfileModel extends Model{

	/**
	 * 通过UID 查询 用户数组 
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public function get($uid)
	{
		$row = $this->cache('getProfile'.$uid, 60* 60)->find($uid);
		return $row;
	}

	/**
	 * 创建新会员PROFILE
	 * @param [type] $data [description]
	 */
	public function set($data)
	{
		$id = $this->add($data);
		//echo $this->getError();

		return $id;
	}

	/**
	 * 更新数据
	 * @param [type] $data [description]
	 */
	public function setUpdate($data, $uid)
	{
		$this->where('uid = %d', array($uid));
		try {
			$id = $this->save($data);
		} catch (Exception $e) {
			echo $e;
			exit();
		}
		// echo $id;
		// exit();
		//删除缓存
		S('get'.$uid, null);
		S('getProfile'.$uid, null);
		S('profile'.$uid, null);
		return $id;
	}
}