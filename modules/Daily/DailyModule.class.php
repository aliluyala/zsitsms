<?php
class DailyModule extends BaseModule
{
	public $baseTable = 'daily_view';//视图表
	//模块描述
	public $describe = '日监视表';
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
			'user_name'           => Array('50','N',false,'登录名'),
			'name'                => Array('50','N',false,'姓名'),
			'groupid'             => Array('50','N',false,'关联组别'),
			'first_dial'          => Array('5','S',false,'首播完成'),
			'appointment'         => Array('5','S',false,'预约完成'),
			'success'             => Array('5','S',false,'成功数'),
			'failure'             => Array('5','S',false,'失败数'),
			'create_date'         => Array('30','D',false,'呼叫时间'),
			'first_remain'        => Array('5','S',false,'首拨剩余数'),
			'appointment_remain'  => Array('5','S',false,'预约剩余数',),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'user_name'  ,
			'name'       ,
			'groupid'    ,
			'first_dial' ,
			'appointment',
			'success'    ,
			'failure'    ,
			'create_date',
			'first_remain',
			'appointment_remain',
			);
	//列表字段
	public $listFields = Array(
			'user_name'         ,
			'name'       ,
			'groupid'    ,
			'first_dial' ,
			'appointment',
			'success'    ,
			'failure'    ,
			'create_date',
			'first_remain',
			'appointment_remain',
			);
	//编辑字段
	public $editFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'user_name'  ,
			'name'       ,
			'groupid'    ,
			'first_dial' ,
			'appointment',
			'success'    ,
			'failure'    ,
			'create_date',
			);
	//默认排序
	public $defaultOrder = Array('id','ASC');
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
		'user_name' =>array('MODULE','User','detailView','id','user_name'),
		'name' =>array('MODULE','User','detailView','id','name'),
		'groupid'	=> array('MODULE','GroupManager','detailView','id','name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'id'; 

	                                                                 
	
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows){
		global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		$list = parent::getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows); 
		if(empty($list)) return false;
		$preset_time = $this->getPresetTime($queryWhere);  
		foreach($list as $k=>$v){
			$list[$k] = array_merge($list[$k],$this->getAccountCount($v['user_name'],$preset_time)); 
		}
		return $list;
	}
	
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids){ //print_R($queryWhere);
		global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		$create_date_where = $this->getCreateDate($queryWhere);
	    $this->createView($create_date_where,$queryWhere); 
		return parent::getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
	}
	
	public function getAccountCount($userid,$preset_time){ 
		  global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		  $sql = "select count(*) as first_remain from accounts where status='FIRST_DIAL' and deleted <> 1 and user_attach={$userid}";
		  $data = $APP_ADODB->GetRow($sql);
		  $return['first_remain'] = $data['first_remain'];
		  $sql = "select count(*) as appointment_remain from accounts where (status='APPOINTMENT_QUOTATION' or status='APPOINTMENT_NON_QUOTATION') and deleted <> 1 and user_attach={$userid} and $preset_time";
		  $data = $APP_ADODB->GetRow($sql);  //echo $sql;exit;
 	      $return['appointment_remain'] = $data['appointment_remain'];
          return $return;	
  
	}
	
	//获取预约时间范围
	public function getPresetTime($queryWhere){
		$preset_time="preset_time<'".date('Y-m-d 23:59:59',time())."'";
		if(empty($queryWhere)) return $preset_time;
        foreach($queryWhere as $k=>$v){
			if($v[0] == 'create_date'){
				if($v[1] == '='){
					$v[1] = '<=';
					$v[2] =date('Y-m-d 23:59:59',strtotime($v[2]));
				}
				$create_time[] = $v;
			}
		}
		if(empty($create_time)) return $preset_time;
		$preset_time = formatSqlWhere($create_time,$this->fields); 
        return str_replace('create_date','preset_time',$preset_time);

	}
	//获取创建时间
	public function getCreateDate($queryWhere){
		if(empty($queryWhere)) return false;
		$create_date = array();
		foreach($queryWhere as $k=>$v){
			if($v[0] == 'create_date'){
				$create_date[] = $v;
			}
		}
		if(empty($create_date)) return false;
		$create_date_where = formatSqlWhere($create_date,$this->fields); 	
		return $create_date_where;
	}
	//创建视图
	public function createView($create_date,$queryWhere){
		$where = empty($create_date)?"create_date ='".date('Y-m-d',time())."'":$create_date; 
		global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		$sql ="drop view if exists daily_exist_view";
		$APP_ADODB->Execute($sql);
		$sql=" create view daily_exist_view  as (select * from daily_analysis where {$where})";  //echo $sql;
		$APP_ADODB->Execute($sql);
		$sql="drop view if exists daily_view";
		$APP_ADODB->Execute($sql);
        if(!empty($create_date) && !empty($queryWhere)){//daily_exist_view的id为主键
			$sql="create view  daily_view as 
		      select d.userid id,u.id user_name,u.id name,u.groupid,first_dial,appointment,success,failure,create_date
		      from users u 
		      left join daily_exist_view as d 
			  on u.id=d.userid;
			 ";
        }else{//users表的id为主键
        	$sql="create view  daily_view as 
		      select u.id id,u.id user_name,u.id name,u.groupid,first_dial,appointment,success,failure,create_date
		      from users u 
		      left join daily_exist_view as d 
			  on u.id=d.userid;
			 ";
        }
		
	     $APP_ADODB->Execute($sql);
	}
	
};



?>
