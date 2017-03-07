<?php
/**
 * MemberModel.class.php
 * 管理后台用户控制器
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-06
 */
namespace Admin\Model;
use Think\Model;

class MemberModel extends Model {

	protected 	$trueTableName 	= 'ad_member';
	public 		$errMsg 	= false;

	/**
	 * 用户登录
	 * @param   $username 用户名
	 * @param   $password 登录密码
	 * @return  $memberInfo 用户信息
	 */
	function login($username, $password){
		$memberInfo 	= $this->where('username = "%s"', $username)->find();
		if(!$memberInfo){
			$this->errMsg 	= '用户为找到!';
			return false;
		}
		$password 	= md5(C('DATA_AUTH_KEY').$password);
		if($memberInfo['password'] != $password){
			$this->errMsg 	= '用户名或密码错误!';
			return false;
		}
		if($memberInfo['status'] != 1){
			$this->errMsg 	= '该用户已被禁用!';
			return false;
		}
		//登录成功
		$memberInfo['login_count'] 	+= 1;
		$memberInfo['last_login_ip']	= get_client_ip(1);
		$memberInfo['last_login_time']	= time();
		$this->save($memberInfo);

		//session注册
		session('member', $memberInfo);

		return $memberInfo;
	}

	/**
	 * 添加用户
	 * @param   $username 用户名
	 * @param   $password 密码
	 * @param   $mobile 手机号
	 * @param   $email 邮箱
	 * @return  $memberInfo 用户信息
	 */
	function addMember($username, $password, $mobile, $email){
		$info 	= array(
			'username'	=> $username,
			'password'	=> md5(C('DATA_AUTH_KEY').$password),
			'mobile'	=> $mobile,
			'email'		=> $email
		);
		$isExt 	= $this->where('username = "%s"', $username)->find();
		if($isExt){
			$this->errMsg 	= '用户已存在!';
			return false;
		}
		$memberId 	= $this->add($info);
		if(!$memberId){
			$this->errMsg 	= '用户添加失败!';
			return false;
		}
		$info['id']	= $memberId;
		return $info;
	}
}