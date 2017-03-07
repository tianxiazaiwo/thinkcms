<?php
/**
 * PublicController.class.php
 * 公共控制器
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-06
 */
namespace Admin\Controller;

class PublicController extends BaseController {

	/**
	 * 用户登录
	 * @param   $username 用户名
	 * @param   $password 登录密码
	 * @param   $remeber_me 记住我
	 * @return  $memberInfo [<description>]
	 */
	function login(){
		$this->unEmptyParam(array('username', 'password'));
		$username 	= $this->iInfo['username'];
		$password 	= $this->iInfo['password'];
		$remeberMe 	= $this->iInfo['remeber_me'];

		$memberInfo 	= D('Member')->login($username, $password);
		if($memberInfo === false)
			$this->outOInfo(D('Member')->errMsg, 0);

		//记录cookie
		if($remeberMe){
			$member  	= array('username' => $username, 'password' => $password);
			safe_cookie('member', $member);
		}
		$this->outOInfo(array('info' => $memberInfo));
	}

	function debug(){
		// dump(D('Menu')->addItem('用户列表', 'member/getList', 1));
		// dump(D('Menu')->addItem('用户添加', 'member/addItem', 2));
		// dump(D('Menu')->addItem('用户变更', 'member/changeItem', 2));
		// dump(D('Menu')->addItem('用户删除', 'member/deleteItem', 2));
		// dump(D('Menu')->addItem('用户详情', 'member/detailItem', 2));
		// $ret 	= D('Group')->changeGroupRules(1, array(1, 2, 3,4 ,5));
		// dump($ret);
		// dump(D('Group')->errMsg);
		// dump(explode(',', 1));
		// dump(D('Group')->addItem('会员管理'));
		// dump(D('Group')->changeRules(1, array(1, 2, 3, 4, 5, 6)));
		// dump(D('Group')->addMemberToGroup(1, 1));
		// dump(D('Group')->addMemberToGroup(1, 2));

		// $Auth 	= new \Think\Auth();
		// // $rule  	= 'admin/member/getlist';
		// // $result 	= $Auth->check($rule, 3, array('in','1,2'), 'url');
		// // dump($result);

		// $rules 	= $Auth->getAuthList(1, '1,2');
		// dump($rules);
		D('Menu')->getList();
	}
}