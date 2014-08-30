<?php
namespace Service\Model;
use Think\Model;

/**
 *  注册控制 相关 表
 */
class ServiceReginfoModel extends Model{


	/**
	 * 通过ID 查看 注册控制详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get()
	{
		$results = F('reginfo');

		if (!$results) {
			$row = $this->field(array('username_true','username_reg','username_must','email_true','email_reg','email_must','phone_true','phone_reg','phone_must','realname_true','realname_reg','realname_must','gender_true','gender_reg','gender_must','age_true','age_reg','age_must','area_true','area_reg','area_must'))->find(1);
			//写入缓存
			F('reginfo', $results);
		}
		return $row;
	}


	/**
	 * 更新数据
	 * @param [type] $data [description]
	 */
	public function setUpdate($data)
	{
		$this->where('id = %d', 1);
		try {
			$id = $this->save($data);
		} catch (Exception $e) {
			echo $e;
			exit();
		}
		// echo $id;
		// exit();
		//删除 缓存 
		F('reginfo', NULL);
		return $id;
	}
}