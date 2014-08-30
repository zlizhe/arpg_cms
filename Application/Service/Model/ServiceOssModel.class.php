<?php
namespace Service\Model;
use Think\Model;

/**
 *  OSS 设置
 */
class ServiceOssModel extends Model{

	/**
	 * 通过ID 查看 设置详情
	 * @param  [type] $id [description]
	 * @return [type]      [description]
	 */
	public function get()
	{
		$row = F('getOss');

		if (!$row) {
			$row = $this->field(array('oss_open','oss_id','oss_key','article_bucket','avatar_bucket'))->find(1);
			//写入缓存
			F('getOss', $row);
		}

		return $row;
	}


	/**
	 * 更新数据
	 * @param [type] $data [description]
	 */
	public function setUpdate($data)
	{
		$this->where('id = 1');
		try {
			$id = $this->save($data);
		} catch (Exception $e) {
			echo $e;
			exit();
		}
		// echo $id;
		// exit();
		//删除 缓存 
		F('getOss', NULL);
		return $id;
	}
}