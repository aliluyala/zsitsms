<?php
class DailyAnalysisSHWYModule extends BaseModule
{
	public $baseTable = 'daily_analysis';
	//模块描述
	public $describe = '日监视表(上海唯佑专用)';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'export'       => '导出',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'userid'      => Array('50','N',false,'关联客户'),
			'groupid'     => Array('50','N',false,'关联组别'),
			'first_dial'  => Array('5','S',false,'首播完成'),
			'appointment' => Array('5','S',false,'预约完成'),
			'success'     => Array('5','S',false,'成功数'),
			'failure'     => Array('5','S',false,'失败数'),
			'create_date' => Array('30','D',false,'呼叫时间'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'userid'     ,
			'groupid'    ,
			'first_dial' ,
			'appointment',
			'success'    ,
			'failure'    ,
			'create_date',
			);
	//列表字段
	public $listFields = Array(
			'userid'     ,
			'groupid'    ,
			'first_dial' ,
			'appointment',
			'success'    ,
			'failure'    ,
			'create_date',
			);
	//编辑字段
	public $editFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'userid'     ,
			'groupid'    ,
			'first_dial' ,
			'appointment',
			'success'    ,
			'failure'    ,
			'create_date',
			);
	//默认排序
	public $defaultOrder = Array('userid','ASC');
	//详情入口字段
	public $enteryField = '';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array();

	//字段关联
	public $associateTo = Array(
		'userid'	=> array('MODULE','User','detailView','id','user_name'),
		'groupid'	=> array('MODULE','GroupManager','detailView','id','name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'userid';

	public function setDailyAnalysis($recordid,$status_new){
		$status_old = $this->getStatus($recordid);
		if($status_new == "SUCCESS"){
			$this->updateCounter("success");
		}elseif($status_new == "FAILED"){
			$this->updateCounter("failure");
		}
		if($status_old == "FIRST_DIAL" && $status_new != $status_old){
			$this->updateCounter("first_dial");
		}elseif(($status_old == "APPOINTMENT_QUOTATION" || $status_old == "APPOINTMENT_NON_QUOTATION") && $status_new != $status_old){
			$this->updateCounter("appointment");
		}
	}

	public function getStatus($recordid){
		global $APP_ADODB,$CURRENT_USER_ID;
		$sql = "SELECT status FROM accounts_shwy WHERE id = {$recordid}";
		$result = $APP_ADODB->Execute($sql);
		return $result ? $result->fields["status"] : FALSE;
	}

	public function updateCounter($status){
		global $APP_ADODB,$CURRENT_USER_ID;
		$this->addCounter();
		$sql = "UPDATE daily_analysis set {$status} = {$status} + 1 WHERE userid = {$CURRENT_USER_ID} AND create_date = CURRENT_DATE";
		$APP_ADODB->Execute($sql);
		return $APP_ADODB->Affected_Rows();
	}
	/**
	 * [addCounter UNIQUE KEY(userid,create_date)]
	 */
	public function addCounter(){
		global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		$recordid  = getNewModuleSeq($this->baseTable);
		$sql = "INSERT INTO daily_analysis(id,userid,groupid,create_date) VALUES({$recordid},{$CURRENT_USER_ID},{$CURRENT_USER_GROUPID},CURRENT_DATE)";
		$APP_ADODB->Execute($sql);
	}

};



?>
