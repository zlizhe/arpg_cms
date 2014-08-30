<?php
namespace Service\Model;
use Think\Model;

/**
 *  网站设置
 */
class ServiceSettingModel extends Model{

	/**
	 * 通过ID 查看 设置详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get()
	{
		$row = $this->field(array('site_name','site_url','mail_server','mail_username','mail_password','img_url','img_avatar','analytics','siteopen','site_themes','site_home'))->find(1);
		return $row;
	}


	/**
	 * 更新数据
	 * @param [type] $data [description]
	 */
	public function setUpdate($data)
	{
		$this->where('sid = 1');
		try {
			$id = $this->save($data);
		} catch (Exception $e) {
			echo $e;
			exit();
		}
		// echo $id;
		// exit();
		//删除 缓存 
		F('_Gset', NULL);
		return $id;
	}
}