<?php
class LibraryCategoryModule extends BaseModule
{
	public $baseTable = 'library_category';
	//模块描述
	public $describe = '知识库分类';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'copyView' => '复制',
			'save' => '保存',
			'delete' => '删除',
			'batchDelete' => '批量删除',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'name'               	  =>Array('5','S',true,'公告标题','',1,100),
			'parent'             	  =>Array('50','N',false,'父级id',0),
			//'count'				      =>Array('5','S',false,'文章数量'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'name'          ,
			'parent'        ,
			);
	//列表字段
	public $listFields = Array(
			'name'          ,
			'parent'        ,
			//'count'			,
			);
	//编辑字段
	public $editFields = Array(
			'name'          ,
			'parent'        ,
			);
	//miss编辑字段
	public $missEditFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'name'          ,
			'parent'        ,
			//'count'			,
			);
	//默认排序
	public $defaultOrder = Array('id','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array();

	//字段关联
	public $associateTo = Array(
		//限制为只有二级分类
		'parent'	  => Array('MODULE','LibraryCategory','detailView','id','name',array('parent'=>0)),
	);
	//模块关联
	public $associateBy = Array(

	);

	//记录权限关联字段名
	//public $shareField = '';
	public $adb;
	public function __construct(){
		global $APP_ADODB;
		$this->adb = $APP_ADODB;
	}

	public function getPostCount($categoryid){
		$sql = "SELECT COUNT(*) FROM library_post WHERE categoryid = {$categoryid}";
		$result = $this->adb->Execute($sql);
		if($result) return $result->fields[0];
		return 0;
	}

	public function getCurrentCategory($id){
		$sql = "SELECT name FROM library_category WHERE id = {$id}";
		$result = $this->adb->Execute($sql);
		if($this->adb->Affected_Rows()) return $result->fields['name'];
		return false;
	}

	public function getCategoryList($parent = 0){
		$result = $this->getChildCategory($parent);
		if($result){
			foreach ($result as $key => $value) {
				if($child = $this->getCategoryList($value['id']))
					$result[$key]['list'] = $child;
				else
					$result[$key]['list'] = FALSE;
			}
		}
		return $result;
	}
	public function getChildCategory($parent = 0){
		$sql = "SELECT * FROM library_category WHERE parent = {$parent}";
		$result = $this->adb->Execute($sql);
		if($result) return $result->getArray();
		return false;
	}
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows){
		$category = parent::getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows);
		/*foreach($category as $key => $val){
			$category[$key]['count'] = $this->getPostCount($val['id']);
		}*/
		return $category;
	}

};



?>