<?php
/**
 * GroupModel.class.php
 * 用户组模型
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-07
 */
namespace Admin\Model;
use Think\Model;

class GroupModel extends Model {

	protected 	$trueTableName 	= 'ad_auth_group';
	public 		$errMsg 	= false;

	/**
	 * 获取组列表
	 * @param   $memberId 	用户ID
	 * @return  $list [<description>]
	 */
	function getList($memberId = false){
		//所有组信息
		$groups 	= $this->cache(true)->select();
		//指定用户组
		if($memberId){
			$uGroups 	= M('ad_auth_group_access', null)
				->where('uid = %d', $memberId)
				->cache(true)
				->getField('group_id', true);

			foreach($groups as &$item){
				if(in_array($item['id'], $uGroups)){
					$item['is_auth']	= 1;
				}
			}
		}

		return $groups;
	}

	/**
	 * 添加用户组
	 * @param   $title 名称
	 * @param   $module 模块
	 * @param   $type 组类型
	 * @param   $decipt 组描述
	 * @return  $groupInfo
	 */
	function addItem($title, $module = 'admin', $type = 1, $decipt = ''){
		$info 	= array(
			'title' 	=> $title,
			'module'	=> $module,
			'type'	=> $type,
			'decipt'	=> $decipt
		);

		$id 	= $this->add($info);
		if($id === false){
			$this->errMsg 	= '添加用户组失败!';
			return false;
		}

		$info['id']	= $id;
		return $info;
	}

	/**
	 * 变更用户组
	 * @param   $groupId 组ID
	 * @param   $groupInfo 用户组信息
	 * @return  $boolean
	 */
	function changeItem($groupId, $groupInfo){
		$result 	= $this->where('id = %d', $groupId)->save($groupInfo);
		if($result === false){
			$this->errMsg 	= '组信息变更失败!';
			return false;
		}
		return true;
	}

	/**
	 * 想组中添加用户
	 * @param   $groupId 组ID
	 * @param   $memberId 用户ID
	 * @return  boolean
	 */
	function addMemberToGroup($groupId, $memberId){
		//批量添加
		if(is_array($groupId)){
			$isExt 	= M('ad_auth_group_access', null)
				->where('uid = %d', $memberId)
				->cache(true)
				->getField('group_id', true);

			$uGroups	= array();
			foreach($groupId as $id){
				if(!in_array($id, $isExt)){
					$uGroups[] 	= array('uid' => $memberId, 'group_id' => $id);
				}
			}
			
			if($uGroups){
				$result 	= M('ad_auth_group_access', null)->addAll($uGroups);
				if($result === false){
					$this->errMsg 	= '用户与组关联信息添加失败!';
					return false;
				}
			}	

			return true;
		}

		//单个添加
		$isExt 	= M('ad_auth_group_access', null)
			->where('uid = %d and group_id = %d', $memberId, $groupId)
			->find();

		if(!$isExt){
			$result 	= M('ad_auth_group_access', null)->add(array('uid' => $memberId, 'group_id' => $groupId));
			if($result === false){
				$this->errMsg 	= '用户与组关联信息添加失败!';
				return false;
			}
		}

		return true;
	}

	/**
	 * 想组中添加权限
	 * @param   $groupId 组ID
	 * @param   $ruleId 权限ID
	 * @return  boolean [<description>]
	 */
	function changeRules($groupId, $ruleIds){
		$groupInfo 	= $this->where('id = %d', $groupId)->find();
		if(!$groupInfo){
			$this->errMsg 	= '组ID无效!';
			return false;
		}

		$groupInfo['rules']	= implode(',', (array)$ruleIds);
		$result 	= $this->save($groupInfo);
		if($result === false){
			$This->errMsg 	= '权限添加失败!';
			return false;
		}
		return true;
	}
}