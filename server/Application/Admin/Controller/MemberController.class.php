<?php
/**
 * MemberController.class.php
 * 管理后台用户控制器
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-06
 */
namespace Admin\Controller;

class MemberController extends BaseController {

	/**
	 * 获取用户信息
	 * @return 	$memberInfo
	 */
	function getInfo(){
		echo 'get user info!';
		dump(C('Member'));
	}
}