<?php
/**
 * BaseController.class.php
 * 基类控制器
 * 
 * @author 	王中艺	<wangzy_smile@qq.com>
 * @date 	2017-03-06
 */
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {
	protected $iInfo = array();
	protected $oInfo = array();

	function __construct(){
		parent::__construct();

		//传入参数获取
		$post 	= file_get_contents('php://input');
		$post 	= json_decode($post, true);
		$this->iInfo 	= I('get.') + I('post.') + (array)$post;

		if(strtolower(CONTROLLER_NAME) == 'public') 	
			return true;

		//登录检测
		$memberInfo 	= session('member');
		if(!$member){
			$cMember 	= safe_cookie('member');
			if(!$cMember){
				$this->outOInfo('登录失效,请重新登陆!', 102);
			}
			$memberInfo 	= D('Member')->login($cMember['username'], $cMember['password']);
			if(!$memberInfo){
				cookie('member', null);
				$this->outOInfo('登录失效,请重新登陆!', 102);
			}
		}
		C('Member', $memberInfo);
		define('IS_ROOT', C('Member.id') == 1);

		//权限检测
		// if(!IS_ROOT){
			$Auth 	= new \Think\Auth();
			$rule  	= strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
			$result 	= $Auth->check($rule, C('Member.id'), array('in','1,2'), 'url');
			if(!$result){
				$this->outOInfo('权限检测失败', 0);
			}
		// }
	}

	/**
     * 非空参数检测
     * @param  arra $params 非空参数组
     * @return null         
     */
    function unEmptyParam($params){
        foreach($params as $param){
            if(!isset($this->iInfo[$param]))
                $this->outOInfo('参数'.$param.'未找到!', 0);
        }
    }

    /**
     * 检测客户端参数
     * @param   $info
     * @return  boolean
     */
    function checkIinfo(){
        $numberP    = array(
            'feed_id', 'reward_score', 'status', 'user_id', 'father_id',
            'course_id', 'chapter_id'
        );
        $arrayP     = array();
        foreach($this->iInfo as $key=>$val){
            //数值类型参数检测
            if(in_array($key, $numberP)){
                if(!is_numeric($val) && $val)
                    $this->outOInfo('参数['.$key.']类型不正确!', 0);
            }
            //数组类型参数检测
            if(in_array($key, $arrayP)){
                if(!is_array($val))
                    $this->outOInfo('参数['.$key.']类型不正确!', 0);
            }
            //手机号检测
            if($key == 'mobile'){
                if(strlen($val) != 11 || !is_numeric($val))
                    $this->outOInfo('手机号类型不正确!', 0);
            }
        }
    }

	/**
     * 输出信息
     * @param   $data   输出数据 or 提示信息
     * @param   $code   运行状态码 1：成功 other：失败
     */
    function outOInfo($data, $code = 1){
        $this->oInfo['code']    = $code;

        if($data){
            if(is_array($data)){
                $this->oInfo['data']    = array_merge((array)$this->oInfo['data'], $data);
            }
            if(is_string($data)){
                $this->oInfo['data']    = $data;
            }
        }else{
            $this->oInfo['data']    = C('ERROR_CODE.'.$code) ? C('ERROR_CODE.'.$code) : '未定义错误!';
        }
        
        echo json_encode($this->oInfo);
        exit;
    }
}