<?php
namespace Member\Model;
use Think\Model;

/**
 *  用户 数据表相关
 */
class MembersModel extends Model{

	/**
	 * 通过UID 查询 用户数组 
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public function get($uid)
	{

		$row = $this->cache('get'.$uid, 60* 60)->find($uid);
		return $row;
	}

	/**
	 * 通过 EMAIL PHONE 或 USERNAME 取 用户
	 * @param  account $account [description]
	 * @return userArr          [description]
	 */
	public function getUserByAccount($account)
	{
		//用户名 手机 和 邮箱  OR
		$condition['username'] = $account;
		$condition['phone'] = $account;
		$condition['email'] = $account;
		$condition['_logic'] = 'OR';

		$row = $this->field(array('uid', 'username', 'email', 'password', 'phone', 'question', 'answer'))
				->where($condition)
				->select();

	    return $row[0];			
	}

	/**
	 * 用户资料
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public function getProfile($uid)
	{


		// members 表 和 members_profile 表中的内容
		$row = $this->table('__MEMBERS__ AS t1')
				->join('LEFT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.uid = t2.uid')
				->where("t1.uid = '%d'", array($uid))
				->field(array('t1.uid', 't1.username', 't1.email', 't1.phone', 't2.realname', 't2.gender', 't2.age', 't1.groupid'))
				->cache('profile'.$uid, 60* 60)
				->select();

		if ($row) {
			//任何时候给REALNAME
			if (!$row[0]['realname']) {
				if ($row[0]['username']) {
					$row[0]['realname'] = $row[0]['username'];
				}else{
					if ($row[0]['email']) {
						$row[0]['realname'] = $row[0]['email'];
					}else{
						$row[0]['realname'] = $row[0]['phone'];
					}
				}
			}
		}

		return $row[0];
	}

	/**
	 * 取所有会员列表
	 * @return [type] [description]
	 */
	public function getMemberList($page, $url, $app=1, $groupid=0, $query='', $count=30)
	{

		$this->table('__MEMBERS__ AS t1')
				->join('RIGHT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.uid = t2.uid')
				->field(array('t1.uid','t1.username','t1.email','t1.phone', 't1.groupid', 't1.regip','t1.regdate','t2.realname','t1.ban'));

		//echo $sql;
		$map['t1.ban'] = $app;

		//按组查询
		if ($groupid) {
			$map['t1.groupid'] = $groupid;
		}

		//搜索会员
		if ($query) {
			//$map['_query'] = "t1.username LIKE %$query%";
			$where['t1.uid'] = array('LIKE', "%$query%");
			$where['t1.username'] = array('LIKE', "%$query%");
			$where['t1.email'] = array('LIKE', "%$query%");
			$where['t1.phone'] = array('LIKE', "%$query%");
			$where['t2.realname'] = array('LIKE', "%$query%");
			$where['_logic'] = 'OR';
			$map['_complex'] = $where;
		}
		
		//数据
		$row = $this->where($map)->order('t1.regdate DESC')->page($page, $count)->select();

		//登录日志
		$loginlogDb = D("Member/MembersLoginlog");
		foreach ($row as $key => $value) {
			$row[$key]['loginArr'] = $loginlogDb->getLastLogin($value['uid']);
			$row[$key]['regLocation'] = getLocation($value['regip']);
		}

		//总数量
		$this->table('__MEMBERS__ AS t1')
				->join('RIGHT JOIN __MEMBERS_PROFILE__ AS t2 ON t1.uid = t2.uid');
		//总数
		$how = $this->where($map)->count();

		//总页数
		$howpage = ceil($how / $count);
		//分页
		$multipage = html_multi($page, $howpage, $url);

    
    	$results = array(
			'row'       => $row,
			'how'       => $how,
			'mulitpage' => $multipage,
    	);


    	//echo $how;
    	//print_r($row);
		return $results;
	}

	/**
	 * 创建新会员
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
		S('profile'.$uid, null);
		return $id;
	}
}