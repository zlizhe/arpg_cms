<?php
namespace Set\Controller;
use Think\Controller;


class EmptyController extends Controller {

	public function _initialize()
	{

		//去登录
        $this->redirect('/set/index');
	}

}