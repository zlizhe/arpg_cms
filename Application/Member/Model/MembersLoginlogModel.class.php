<?php
namespace Member\Model;
use Think\Model;

/**
* 	登录日志	
*/
class MembersLoginlogModel extends Model
{
	
	/**
	 * 设置最后登录
	 */
	public function set($uid)
	{
		$data = array(
			'uid'		=> $uid,
			'loginip'	=> get_client_ip(),
			'logindate'	=> getTime(),
		);
		$id = $this->add($data);
		return $id;
	}

	/**
	 * 取用户上次登录数据
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public function getLastLogin($uid)
	{
		$row = $this->where("uid = '%d'", array($uid))->field(array('loginip','logindate'))->find();

		if ($row['loginip']) {
			//最后登录地点
			$row['loginLocation'] = getLocation($row['loginip']); 
		}else{
			$row['loginLocation'] = '未知';
		}
		
		return $row;
	}

}