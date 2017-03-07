<?php
/**
 * RuleModel.class.php
 * 权限控制器
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-07
 */
namespace Admin\Model;
use Think\Model;

class RuleModel extends Model {

	protected 	$trueTableName = 'ad_auth_rule';
	public 		$errMsg 	= false;

	/**
	 * 添加权限规则
	 * @param   $title 标题
	 * @param   $name 路径
	 * @param   $module 模块
	 * @param   $type 类型
	 * @return  $ruleInfo
	 */
	function addItem($title, $name, $module, $type){
		$info 	= array(
			'title'	=> $title,
			'name'	=> $name,
			'module'	=> $module,
			'type'	=> $type
		);

		$isExt 	= $this->where('name = "%s"', $name)->find();
		if($isExt){
			$this->errMsg 	= '权限已存在!';
			return false;
		}

		$id 	= $this->add($info);
		$info['id']	= $id;
		return $info;
	}

	/**
	 * 获取权限信息
	 * @param   $id or $name id或者name
	 * @return  $ruleInfo
	 */
	function getItem($param){
		if(is_numeric($param)){
			$ruleInfo 	= $this->where('id = %d', $param)->find();
		}else{
			$ruleInfo 	= $this->where('name = "%s"', $param)->find();
		}

		return $ruleInfo;
	}
}