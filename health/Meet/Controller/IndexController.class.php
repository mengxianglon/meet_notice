<?php
namespace Meet\Controller;

use Think\Controller;

class IndexController extends Controller
{
	public function _initialize()
	{
        $behaviorReturn = \Think\Hook::listen('app_meet');
	}
	public function index(){
		$this->display();
	}
	public function video(){
		$this->display();
	}
	public function details(){
		$this->display();
	}
}