<?php
/**
 * MenuModel.class.php
 * 菜单模型
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-06
 */

namespace Admin\Model;
use Think\Model;

class MenuModel extends Model{

	protected 	$trueTableName 	= 'ad_menu';
	public 		$errMsg 	= false;

	/**
	 * 添加菜单
	 * @param   $title 菜单名称
	 * @param   $url 路径
	 * @param   $pid 父ID
	 * @param   $sort 排序
	 * @param   $hide 是否隐藏
	 * @return  $menuInfo
	 */
	function addItem($title, $url, $pid = 0, $sort = 1, $hide = 0){
		//权限添加
		$rModule 	= 'admin';
		$rType 	= 2;
		$rName 	= 'admin/'.$url;
		$rTitle = $title;
		$ruleInfo 	= D('Rule')->addItem($rTitle, $rName, $rModule, $rType);
		if($ruleInfo === false){
			$ruleInfo 	= D('Rule')->getItem($rName);
			if($ruleInfo === false){
				$this->errMsg 	= '权限添加失败!';
				return false;
			}
		}
		
		//菜单添加
		$info 	= array(
			'title'	=> $title,
			'url'	=> $url,
			'pid'	=> $pid,
			'sort'	=> $sort,
			'hide'	=> $hide,
			'rule_id'	=> $ruleInfo['id']
		);
		$id 	= $this->add($info);
		$info['id']	= $id;

		return $info;
	}

	/**
	 * 获取菜单列表
	 * @param   $tree 是否树状显示
	 * @param   $memberId 检测权限
	 * @return  $list
	 */
	function getList($tree = true, $memberId = false){
		$list 	= $this->cache(true)
			->where('status = 1')
			->order('pid, sort')
			->getField('id, rule_id, title, pid, sort, url, hide, status, icon');

		//权限检测
		$Auth 	= new \Think\Auth();
		foreach($list as &$item){
			if($memberId){
				$rule 	= 'admin/'.$item['url'];
				if($Auth->check($rule, $memberId, array('in','1,2'), 'url')){
					$item['is_auth']	= 1;
					continue;
				}
			}
			$item['is_auth']	= 0;
		}

		if($tree){
			$list[0]	= array();
			foreach($list as &$item){
				$list[$item['pid']]['childs'][] 	= &$item;
			}

			$list 	= $list[0]['childs'];
		}
		dump($list);
	}
}
