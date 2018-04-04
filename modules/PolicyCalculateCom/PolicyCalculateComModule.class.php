<?php
class PolicyCalculateComModule extends BaseModule
{
	public $baseTable = 'policy_draft_com';

	//模块描述
	public $describe = '算价器(新版通用)';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'           	=> '浏览',
			'detailView'   		=> '详情',
			'editView'     		=> '编辑',
			'createView'  		=> '新建',
			'copyView'    		=> '复制',
			'save'         		=> '保存',
			'delete'      		=> '删除',
			'import'      		=> '导入',
			'export'      		=> '导出',
			'batchDelete'  		=> '批量删除',
			'sendPolicySMS'		=> '发送短信',
			'associateListView' => '算价页面',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'cal_no'           => Array('17','S',true,'算价纪录编号','CAL'),
			'vin_no'           => Array('5','S',true,'车辆识别码',''),
            'summary'          => Array('5','S',false,'摘要',''),
			'content'          => Array('5','S',false,'内容',''),
			'associate_userid' => Array('55','N',false,'记录归属于组或用户，将决定其它用户访问此记录的权限'),
			'create_time'      => Array('35','DT',false,'记录创建的时间'),
			'create_userid'    => Array('51','N',false,'创建记录的操作员'),
			'modify_time'      => Array('36','DT',false,'最后一次修改记录的时间'),
			'modify_userid'    => Array('52','N',false,'最后一次修改记录的操作员'),

			'form[NO]'                                               => Array('17','S',true,'','CAL'),
			'form[OTHER][PREMIUM_RATE_TABLE]'                        => Array('20','E',true,'',''),
			'form[OTHER][INSURANCE_COMPANY]'                         => Array('101','S',false,'',''),
			'form[AUTO][DISCOUNT_PRICE]'                             => Array('8','S',false,'','0.00',0,1000000000,'70px','元',2),
			'form[AUTO][MOBILE]'                                     => Array('7','S',true,'','','1','30','178px'),
			'form[HOLDER][HOLDER]'                                   => Array('7','S',true,'','','1','30','175px'),
			'form[HOLDER][HOLDER_IDENTIFY_TYPE]'                   	 => Array('101','S',true,'',''),
			'form[HOLDER][HOLDER_ADDRESS]'                         	 => Array('101','S',true,'',''),
			'form[HOLDER][HOLDER_IDENTIFY_NO]'                     	 => Array('7','S',false,'','',1,19,'179px','^(\d{8}[0-9 a-z]|\d{8}[-][0-9 a-z]|\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$','身份证号码或机构代码长度错误!'),
			'form[INSURANT][INSURANT]'                               => Array('101','S',true,'',''),
			'form[INSURANT][INSURANT_IDENTIFY_TYPE]'                 => Array('101','S',true,'',''),
			'form[INSURANT][INSURANT_ADDRESS]'                       => Array('101','S',true,'',''),
			'form[INSURANT][INSURANT_IDENTIFY_NO]'                   => Array('101','S',true,'',''),
			'form[AUTO][LICENSE_NO]'                                 => Array('7','S',true,'','',7,8,'95px','^[\u4e00-\u9fa5][a-zA-Z][a-zA-Z0-9]{5}|^[\u4e00-\u9fa5][a-zA-Z][a-zA-Z0-9]{6}$','车牌号码格式如：川A12345'),
			'form[AUTO][LICENSE_TYPE]'                               => Array('20','E',true,'',''),
			'form[AUTO][OWNER]'                                      => Array('7','S',true,'','',0,255,'178px'),
			'form[AUTO][VIN_NO]'                                     => Array('7','S',true,'','',1,17,'160px','^[a-zA-Z0-9]{1,17}$','车辆识别码由17位字母数字组成'),
			'form[AUTO][ENGINE_NO]'                                  => Array('7','S',true,'','',0,255,'160px'),
			'form[AUTO][MODEL]'                                      => Array('7','S',true,'','',0,255,'193px'),
			'form[AUTO][ENGINE]'                                     => Array('8','N',false,'','0.0000',0,500,'40px','L',4),
			'form[AUTO][SEATS]'                                      => Array('8','N',false,'','5',1,100,'30px','人',0),
			'form[AUTO][KERB_MASS]'                                  => Array('8','N',false,'','0',0,100000,'40px','KG',0),
			'form[AUTO][MODEL_CODE]'                                 => Array('7','S',true,'','',0,255,'160px'),
			'form[AUTO][BUYING_PRICE]'                               => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[AUTO][ENROLL_DATE]'                                => Array('30','S',true,'',''),
			'form[AUTO][VEHICLE_TYPE]'                               => Array('20','E',true,'',''),
			'form[AUTO][USE_CHARACTER]'                              => Array('20','E',true,'',''),
			'form[AUTO][ORIGIN]'                                     => Array('20','E',true,'',''),
			'form[AUTO][TONNAGE]'                                    => Array('8','S',false,'','0',0,100000,'40px','KG',0),
			'form[POLICY][TOTAL_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][TOTAL_STANDARD_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][TOTAL_BUSINESS_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][TOTAL_MVTALCI_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][TOTAL_TRAVEL_TAX_PREMIUM]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][TRAVEL_TAX_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][MVTALCI_SELECT]'                           => Array('23','E',true,'',''),
			'form[POLICY][MVTALCI_PREMIUM]'                          => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][MVTALCI_DISCOUNT]'                         => Array('8','N',false,'','1.0000',0,2,'70px','',4),
			'form[POLICY][FLOATING_RATE]'                            => Array('20','E',false,'','A4'),
			'form[POLICY][MVTALCI_START_TIME]'                       => Array('31','DT',true,'',''),
			'form[POLICY][MVTALCI_END_TIME]'                         => Array('31','DT',true,'',''),
			'form[POLICY][BUSINESS_PREMIUM]'                         => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][BUSINESS_DISCOUNT_PREMIUM]'                => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][TOTAL_DEDUCTIBLE]'                		 => Array('8','N',false,'','0.00',0,1000000000,'40px','元',2),

			'form[POLICY][BUSINESS_DISCOUNT]'                        => Array('8','N',false,'','1.0000',0,2,'70px','',4),
			'form[POLICY][BUSINESS_CUSTOM_DISCOUNT]'                 => Array('8','N',false,'','1.0000',0,2,'70px','',4),

			'form[POLICY][BUSINESS_STANDARD_PREMIUM]'                => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),

			'form[POLICY][BUSINESS_START_TIME]'                      => Array('31','DT',true,'',''),
			'form[POLICY][BUSINESS_END_TIME]'                        => Array('31','DT',true,'',''),

			'form[POLICY][DESIGNATED_DRIVER1]'                       => Array('23','S',true,'',''),
			'form[POLICY][DRIVER_NAME1]'                             => Array('7','S',true,'','',0,255,'170px'),
			'form[POLICY][DRIVING_LICENCE_NO1]'                      => Array('7','S',true,'','',18,18,'170px','^[0-9]{17}[Xx0-9]','驾驶证号码为18位数字,最后一位可以是X'),
			'form[POLICY][DRIVER_ALLOW_DRIVE1]'                      => Array('20','E',true,'','C1'),
			'form[POLICY][DRIVER_SEX1]'                              => Array('20','E',true,'',''),
			'form[POLICY][DRIVER_AGE1]'                              => Array('20','E',true,'',''),
			'form[POLICY][DRIVING_YEARS1]'                           => Array('20','E',true,'',''),

			'form[POLICY][DESIGNATED_DRIVER2]'                       => Array('23','E',true,'',''),
			'form[POLICY][DRIVER_NAME2]'                             => Array('7','S',true,'','',0,255,'170px'),
			'form[POLICY][DRIVING_LICENCE_NO2]'                      => Array('7','S',true,'','',18,18,'170px','^[0-9]{17}[Xx0-9]','驾驶证号码为18位数字,最后一位可以是X'),
			'form[POLICY][DRIVER_ALLOW_DRIVE2]'                      => Array('20','E',true,'','C1'),
			'form[POLICY][DRIVER_SEX2]'                              => Array('20','E',true,'',''),
			'form[POLICY][DRIVER_AGE2]'                              => Array('20','E',true,'',''),
			'form[POLICY][DRIVING_YEARS2]'                           => Array('20','E',true,'',''),
			'form[AUTO][INDUSTY_MODEL_CODE]'						 => Array('8','S',false,'','0','','40px'),

			'form[AUTO][INDUSTY_CODE]'						 		 => Array('8','S',false,'','0','','40px'),//行业车型代码

			'form[POLICY][DESIGNATED_DRIVER3]'                       => Array('23','E',true,'',''),
			'form[POLICY][DRIVER_NAME3]'                             => Array('7','S',true,'','',0,255,'170px'),
			'form[POLICY][DRIVING_LICENCE_NO3]'                      => Array('7','S',true,'','',18,18,'170px','^[0-9]{17}[Xx0-9]','驾驶证号码为18位数字,最后一位可以是X'),
			'form[POLICY][DRIVER_ALLOW_DRIVE3]'                      => Array('20','E',true,'','C1'),
			'form[POLICY][DRIVER_SEX3]'                              => Array('20','E',true,'',''),
			'form[POLICY][DRIVER_AGE3]'                              => Array('20','E',true,'',''),
			'form[POLICY][DRIVING_YEARS3]'                           => Array('20','E',true,'',''),

			'form[POLICY][YEARS_OF_INSURANCE]'                       => Array('20','E',true,'',''),
			'form[POLICY][CLAIM_RECORDS]'                            => Array('20','E',true,'',''),
			'form[POLICY][DRIVING_AREA]'                             => Array('20','E',true,'',''),
			'form[POLICY][AVERAGE_ANNUAL_MILEAGE]'                   => Array('20','E',true,'',''),
			'form[POLICY][MULTIPLE_INSURANCE]'                       => Array('20','E',false,'','MULTIPLE_INSURANCE_1'),
			'form[POLICY][BUSINESS_DISCOUNT_SHWY]'                   => Array('8','N',false,'','1.000',0,2,'70px','',3),

			'form[POLICY][TVDI_INSURANCE_AMOUNT]'                    => Array('8','N',false,'','0',0,1000000000,'70px','',0),
			'form[POLICY][DOC_AMOUNT]'                               => Array('20','E',true,'',''),
			'form[POLICY][TVDI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TVDI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_INSURANCE_AMOUNT]'                   => Array('20','E',true,'','30'),
			'form[POLICY][TTBLI_INSURANCE_AMOUNT_EXT]'               => Array('8','N',false,'','120',100,800,'40px','万',0),
			'form[POLICY][TTBLI_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_DISCOUNT_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TWCDMVI_INSURANCE_AMOUNT]'                 => Array('8','N',false,'','0',0,1000000000,'70px','',0),
			'form[POLICY][TWCDMVI_PREMIUM]'                          => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TWCDMVI_DISCOUNT_PREMIUM]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_INSURANCE_DRIVER_AMOUNT]'            => Array('8','N',false,'','10000',0,1000000000,'70px','',0),
			'form[POLICY][TCPLI_DRIVER_PREMIUM]'                     => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_DRIVER_DISCOUNT_PREMIUM]'            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_INSURANCE_PASSENGER_AMOUNT]'         => Array('8','N',false,'','10000',0,1000000000,'70px','',0),
			'form[POLICY][TCPLI_PASSENGER_PREMIUM]'                  => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_PASSENGER_COUNT]'                    => Array('8','N',false,'','4',0,100,'30px','',0),
			'form[POLICY][TCPLI_PASSENGER_DISCOUNT_PREMIUM]'         => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BSDI_INSURANCE_AMOUNT]'                    => Array('20','E',false,'','0'),
			'form[POLICY][BSDI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BSDI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_INSURANCE_AMOUNT]'                    => Array('8','N',false,'','0',0,1000000000,'70px','',0),
			'form[POLICY][SLOI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][GLASS_ORIGIN]'                             => Array('20','E',true,'',''),
			'form[POLICY][BGAI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BGAI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_INSURANCE_AMOUNT]'                   => Array('8','N',false,'','0',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_DISCOUNT_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_DEVICE_LIST]'                        => Array('101','S',false,'','[]'),
			'form[POLICY][STSFS_RATE]'                               => Array('20','E',false,'',''),
			'form[POLICY][STSFS_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][STSFS_DISCOUNT_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TVDI_NDSI_PREMIUM]'                        => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TVDI_NDSI_DISCOUNT_PREMIUM]'               => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_NDSI_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_NDSI_DISCOUNT_PREMIUM]'              => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TWCDMVI_NDSI_PREMIUM]'                     => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TWCDMVI_NDSI_DISCOUNT_PREMIUM]'            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_DRIVER_NDSI_PREMIUM]'                => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_DRIVER_NDSI_DISCOUNT_PREMIUM]'       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_PASSENGER_NDSI_PREMIUM]'             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_PASSENGER_NDSI_DISCOUNT_PREMIUM]'    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BSDI_NDSI_PREMIUM]'                        => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BSDI_NDSI_DISCOUNT_PREMIUM]'               => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BGAI_NDSI_PREMIUM]'                        => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BGAI_NDSI_DISCOUNT_PREMIUM]'               => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_NDSI_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_NDSI_DISCOUNT_PREMIUM]'              => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][STSFS_NDSI_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][STSFS_NDSI_DISCOUNT_PREMIUM]'              => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_NDSI_PREMIUM]'                        => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_NDSI_DISCOUNT_PREMIUM]'               => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),

			'form[POLICY][VWTLI_INSURANCE_AMOUNT]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][VWTLI_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][VWTLI_DISCOUNT_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][VWTLI_NDSI_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][VWTLI_NDSI_DISCOUNT_PREMIUM]'              => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM1_INSURANCE_NAME]'                   => Array('7','S',true,'','',0,255,'170px'),
			'form[POLICY][CUSTOM1_INSURANCE_AMOUNT]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM1_PREMIUM]'                          => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM1_DISCOUNT_PREMIUM]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM1_NDSI_PREMIUM]'                     => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM1_NDSI_DISCOUNT_PREMIUM]'            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM2_INSURANCE_NAME]'                   => Array('7','S',true,'','',0,255,'170px'),
			'form[POLICY][CUSTOM2_INSURANCE_AMOUNT]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM2_PREMIUM]'                          => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM2_DISCOUNT_PREMIUM]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM2_NDSI_PREMIUM]'                     => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][CUSTOM2_NDSI_DISCOUNT_PREMIUM]'            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),

			'form[POLICY][RDCCI_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][RDCCI_INSURANCE_UNIT]'                     => Array('8','N',false,'','0',0,1000000000,'30px','',0),
			'form[POLICY][RDCCI_INSURANCE_QUANTITY]'                 => Array('8','N',false,'','0',0,1000000000,'30px','',0),
			'form[POLICY][RDCCI_INSURANCE_AMOUNT]'                   => Array('8','N',false,'','0',0,1000000000,'50px','',0),

			'form[POLICY][MVLINFTPSI_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_DOUBLE_PREMIUM]'                     => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BUSINESS]'			                     => Array('5','S',false,'',''),
			//'form[OTHER][SHIYEICAO_PRODUCT]'                         => Array('27','E',false,'',''),
			'form[OTHER][GIFT]'                                      => Array('9','S',false,'',''),
			'form[OTHER][REMARKS]'                                   => Array('9','S',false,'',''),

			'form[OTHER][DZA_DEMANDNOS]'                         	=> Array('8','S',false,'','0',0,1000000000,'70px','',2),
			'form[OTHER][DZA_CHECKCODES]'                        	=> Array('8','S',false,'','0',0,1000000000,'70px','',2),
			'form[OTHER][DAA_DEMANDNOS]'                         	=> Array('8','S',false,'','0',0,1000000000,'70px','',2),
			'form[OTHER][DAA_CHECKCODES]'                        	=> Array('8','S',false,'','0',0,1000000000,'70px','',2),
			'form[AUTO][MODEL_ALIAS]'								=> Array('8','S',false,'','0',0,1000000000,'70px','',2),
			);

	//安全字段,可以控制权限
	public $safeFields = Array(
			);
	//列表字段
	public $listFields = Array(
			'cal_no'   				  ,
			'vin_no'   				  ,
			'summary'  				  ,
			'associate_userid'        ,
            'create_time'             ,
			);
	//编辑字段
	public $editFields = Array(
			'cal_no'   				  ,
			'vin_no'   				  ,
			'summary'  				  ,
			'content'                 ,
			'associate_userid'        ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'cal_no'   				  ,
			'vin_no'   				  ,
			);

	//允许批量修改字段
	public $batchEditFields = Array();
	//允许miss编辑字段
	public $missEditFields = Array(
			);
	//默认排序
	public $defaultOrder = Array('cal_no','DESC');
	//详情入口字段
	public $enteryField = 'cal_no';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		'form[AUTO][LICENSE_TYPE]' => array(
		                'SMALL_CAR'                    ,
		                'LARGE_AUTOMOBILE'             ,
		                'TRAILER'                      ,
		                'EMBASSY_CAR'                  ,
		                'CONSULATE_VEHICLE'            ,
		                'HK_MACAO_ENTRY_EXIT_CAR'      ,
		                'COACH_CAR'                    ,
						'POLICE_CAR'                   ,
		                'GENERAL_MOTORCYCLE'           ,
		                'MOPED'                        ,
		                'EMBASSY_MOTORCYCLE'           ,
		                'CONSULATE_MOTORCYCLE'         ,
		                'COACH_MOTORCYCLE'             ,
		                'POLICE_MOTORCYCLE'            ,
		                'TEMPORARY_VEHICLE'            ,

		                ),
		'form[INSURANT][IDENTIFY_TYPE]' => array(
						'IDENTITY_CARD'                               ,
						'RESIDENCE_BOOKLET'                           ,
						'PASSPORT'                                    ,
						'MILITARY_DOCUMENTS'                          ,
						'DRIVER_LICENSE'                              ,
						'HOME_CARD'                                   ,
						'HK_IDENTITY_CARD'                            ,
						'WORK_NO'                                     ,
						'TO_TAIWAN_PASS'                              ,
						'HK_MACAO_PASS'                               ,
						'SOLDIER_CARD'                                ,
						'HK_MACAO_RESIDENTS_TRAVELING_MAINLAND_PASSES',
						'TAIWAN_RESIDENTS_TRAVELING_MAINLAND_PASSES'  ,
						'ORGANIZATION_CODE_CERTIFICATE'               ,
						'OTHER'                                       ,
						),

		'form[INSURANT][HOLDER_IDENTIFY_TYPE]' => array(
						'IDENTITY_CARD'                               ,
						'RESIDENCE_BOOKLET'                           ,
						'PASSPORT'                                    ,
						'MILITARY_DOCUMENTS'                          ,
						'DRIVER_LICENSE'                              ,
						'HOME_CARD'                                   ,
						'HK_IDENTITY_CARD'                            ,
						'WORK_NO'                                     ,
						'TO_TAIWAN_PASS'                              ,
						'HK_MACAO_PASS'                               ,
						'SOLDIER_CARD'                                ,
						'HK_MACAO_RESIDENTS_TRAVELING_MAINLAND_PASSES',
						'TAIWAN_RESIDENTS_TRAVELING_MAINLAND_PASSES'  ,
						'ORGANIZATION_CODE_CERTIFICATE'               ,
						'OTHER'                                       ,
						),

		'form[AUTO][VEHICLE_TYPE]' => Array(
		                    'PASSENGER_CAR'              ,
							'TRUCK'                      ,
							'SEMI_TRAILER_TOWING'        ,
							'THREE_WHEELED'              ,
							'LOW_SPEED_TRUCK'            ,
							'VAN'                        ,
							'DUMP_TRAILER'               ,
		                    'FUEL_TANK_CAR'              ,
		                    'TANK_CAR'                   ,
		                    'THE_LIQUID_TANK'            ,
		                    'REFRIGERATED'               ,
		                    'TANK_TRAILER'               ,
		                    'BULLDOZER'                  ,
		                    'WRECKER'                    ,
		                    'SWEEPER'                    ,
		                    'CLEAN_THE_CAR'              ,
		                    'CARRIAGE_HOIST'             ,
		                    'LOADING_AND_UNLOADING'      ,
		                    'LIFT_TRUCK'                 ,
		                    'CONCRETE_MIXER_TRUCK'       ,
		                    'MINING_VEHICLE'             ,
		                    'PROFESSIONAL_TRAILER'       ,
		                    'SPECIAL_TWO_TRAILER'        ,
		                    'SPECIAL_TWO_OTHER'          ,
		                    'TV_TRUCKS'                  ,
		                    'FIRE_ENGINE'                ,
		                    'MEDICAL_VEHICLE'            ,
		                    'OIL_STEAM'                  ,
		                    'ROAD_VEHICLES'              ,
		                    'MINE_CAR'                   ,
		                    'ARMORED_CAR'                ,
		                    'AMBULANCE'                  ,
		                    'MONITORING_CAR'             ,
		                    'RADAR_VEHICLE'              ,
		                    'X_OPTICAL_CAR'              ,
		                    'TELECOM_ENGINEERING'        ,
		                    'ELECTRICAL_ENGINEERING'     ,
		                    'PROFESSIONAL_NET_WATERWHEEL',
		                    'INSULATION_CAR'             ,
		                    'POSTAL_CAR'                 ,
		                    'POLICE_SPECIAL_VEHICLE'     ,
		                    'CONCRETE_PUMP_TRUCK'        ,
		                    'SPECIAL_THREE_TRAILER'      ,
		                    'SPECIAL_THREE_OTHER'        ,
		                    'CONTAINER_TRACTORS'         ,
		                    'MOTORCYCLE'                 ,
		                    'THREE_MOTORCYCLE'           ,
		                    'SIDECAR'                    ,
		                    'TRACTOR'                    ,
		                    'COMBINE_HARVESTER'          ,
		                    'OTHER_VEHICLES'             ,
                            ),
		'form[AUTO][USE_CHARACTER]' => Array(
		                    'NON_OPERATING_PRIVATE'        	,
		                    'NON_OPERATING_ENTERPRISE'     	,
		                    'NON_OPERATING_AUTHORITY'      	,
		                    'OPERATING_LEASE_RENTAL'       	,
		                    'OPERATING_CITY_BUS'           	,
		                    'OPERATING_HIGHWAY_BUS'        	,
		                    'NON_OPERATING_TRUCK'          	,
		                    'OPERATING_TRUCK'              	,
		                    'NONE_OPERATING_TRAILER'       	,
		                    'OPERATING_TRAILER'            	,
		                    'SPECIAL_AUTO'                 	,
		                    'MOTORCYCLE'                   	,
		                    'DUAL_PURPOSE_TRACTOR'         	,
		                    'TRANSPORT_TRACTOR'            	,
		                    'OPERATING_LOW_SPEED_TRUCK'    	,
		                    'NON_OPERATING_LOW_SPEED_TRUCK'	,
						    ),
		'form[AUTO][ORIGIN]' => Array(
							'DOMESTIC',
							'IMPORTED',
							'JOINT_VENTURE'
							),
		'form[POLICY][STSFS_RATE]' => Array(
							'DOMESTIC',
							'IMPORTED',
							'JOINT_VENTURE'
							),

		'form[POLICY][DRIVING_AREA]'  => array(
							'THE_TERRITORY_OF',
							'THE_PROVINCE',
							'THE_FIXED_LINE',
							'THE_FLOOR',
							),
		'form[POLICY][DRIVER_AGE1]'=>array(
							"LESS_25_AGE" ,
							"25_30_AGE"     ,
							"30_40_AGE"     ,
							"40_60_AGE"     ,
							"GREATER_60_AGE"    ,
							),
		'form[POLICY][DRIVER_AGE2]'=>array(
							"LESS_25_AGE" ,
							"25_30_AGE"     ,
							"30_40_AGE"     ,
							"40_60_AGE"     ,
							"GREATER_60_AGE"     ,
							),
		'form[POLICY][DRIVER_AGE3]'=>array(
							"LESS_25_AGE" ,
							"25_30_AGE"     ,
							"30_40_AGE"     ,
							"40_60_AGE"     ,
							"GREATER_60_AGE"     ,
							),
		'form[POLICY][DRIVER_SEX1]' =>array('MALE','FEMALE'),
		'form[POLICY][DRIVER_SEX2]' =>array('MALE','FEMALE'),
		'form[POLICY][DRIVER_SEX3]' =>array('MALE','FEMALE'),
		'form[POLICY][DRIVING_YEARS2]' => array(
							"LESS_1_YEARS"   ,
							"1_3_YEARS"      ,
							"GREATER_3_YEARS",
							),
		'form[POLICY][DRIVING_YEARS1]' => array(
							"LESS_1_YEARS"   ,
							"1_3_YEARS"      ,
							"GREATER_3_YEARS",
							),
		'form[POLICY][DRIVING_YEARS3]' => array(
							"LESS_1_YEARS"   ,
							"1_3_YEARS"      ,
							"GREATER_3_YEARS",
							),
		'form[POLICY][DRIVER_ALLOW_DRIVE1]' => array('DAD_A1','DAD_A2','DAD_A3','DAD_B1','DAD_B2','DAD_C1','DAD_C2','DAD_C3','DAD_C4','DAD_C5','DAD_D','DAD_E','DAD_F','DAD_M','DAD_N','DAD_P'),
		'form[POLICY][DRIVER_ALLOW_DRIVE2]' => array('DAD_A1','DAD_A2','DAD_A3','DAD_B1','DAD_B2','DAD_C1','DAD_C2','DAD_C3','DAD_C4','DAD_C5','DAD_D','DAD_E','DAD_F','DAD_M','DAD_N','DAD_P'),
		'form[POLICY][DRIVER_ALLOW_DRIVE3]' => array('DAD_A1','DAD_A2','DAD_A3','DAD_B1','DAD_B2','DAD_C1','DAD_C2','DAD_C3','DAD_C4','DAD_C5','DAD_D','DAD_E','DAD_F','DAD_M','DAD_N','DAD_P'),


		'form[POLICY][CLAIM_RECORDS]'=>array(
							"MORE_TWO_YEARS_NO_CLAIM"   ,
							"TWO_YEAR_NO_CLAIM"         ,
							"LAST_YEAR_NO_CLAIM"        ,
							"FIRST_YEAR_INSURANCE"      ,
							"LAST_YEAR_CLAIM_ONE"       ,
							"LAST_YEAR_CLAIM_TWO"       ,
							"LAST_YEAR_CLAIM_THREE"     ,
							"LAST_YEAR_CLAIM_FOUR"      ,
							"LAST_YEAR_CLAIM_FIVE_ABOVE",
							),
		'form[POLICY][YEARS_OF_INSURANCE]' => array(
							"FIRST_YEAR_INSURANCE",
							"RENEWAL_OF_INSURANCE",
							),
		'form[POLICY][AVERAGE_ANNUAL_MILEAGE]'=>array(
							"LESS_30000_KM"       ,
							"30000_50000_KM"      ,
							"GREATER_50000_KM_1.1",
							"GREATER_50000_KM_1.2",
							"GREATER_50000_KM_1.3",
							),
		'form[POLICY][DOC_AMOUNT]' => array('0','300','500','1000','2000'),
		'form[POLICY][TTBLI_INSURANCE_AMOUNT]'=>array(
							"5"   ,
							"10"  ,
							"15"  ,
							"20"  ,
							"30"  ,
							"50"  ,
							"100" ,
							"150" ,
							"200" ,
							//"100+",
							),
		'form[POLICY][BSDI_INSURANCE_AMOUNT]'=>array('2000','5000','10000','20000'),
		'form[POLICY][GLASS_ORIGIN]' => array("DOMESTIC","DOMESTIC_SPECIAL","IMPORTED","IMPORTED_SPECIAL"),
		'form[POLICY][FLOATING_RATE]' => array('A1','A2','A3','A4','A5','A6'),
		'form[POLICY][MULTIPLE_INSURANCE]' => array(
							'MULTIPLE_INSURANCE_1',
							'MULTIPLE_INSURANCE_0.99',
							'MULTIPLE_INSURANCE_0.98',
							'MULTIPLE_INSURANCE_0.97',
							'MULTIPLE_INSURANCE_0.96',
							'MULTIPLE_INSURANCE_0.95',
							),
	);

	//字段关联
	public $associateTo = Array(
		'create_userid' => Array('MODULE','User','detailView','id','user_name'),
		'modify_userid' => Array('MODULE','User','detailView','id','user_name'),
	);

	//模块关联
	public $associateBy = Array();

	//记录权限关联字段名
	public $shareField = 'associate_userid';

	public function autoCompleteFieldValue($field,$pfx)
	{
		if($field == 'cal_no')
		{
			return  $pfx.date_format(date_create(),'YmdHis');
		}
		return parent::autoCompleteFieldValue($field,$pfx);
	}

	//取得算价器配置
	public function getPCConfig()
	{
		return  require_once(_ROOT_DIR.'/config/policy_calculate.conf.php');
	}

	//取得算价器设置
	public function getPCSetting()
	{
		global $APP_ADODB,$CURRENT_USER_GROUPID;
		$sql = "select * from policy_calculate_setting";
		$result = $APP_ADODB->Execute($sql);
		$set = array();
		if($result && !$result->EOF)
		{
			$set = json_decode($result->fields['setting'],true);
		}
		return $set;
	}

	//创建算价接口对象
	public function createPCObj($api=null)
	{
		$set = $this->getPCSetting();
		if(empty($set)) return false;
		require_once(_ROOT_DIR.'/include/PremiumCal/PremiumCal.class.php');
		$pcapiconf = array(
			'available_api' => array(),//$set['allow_apis'],
			'default_api' => $set['default_api'],
			'api_configs' => $set['api_setitems'],
		);
		$cachepath = _ROOT_DIR.'/cache/PremiumCal';
		if(!is_dir($cachepath)) mkdir($cachepath,0777,true);

		return new PremiumCal($pcapiconf,$cachepath,$api);
	}

	//获取算价记录列表信息
	public function getCalList($vin,$userid = NULL)
	{
		global $APP_ADODB;
		$affix = '';
		if($userid !== NULL) $affix = "and associate_userid={$userid}";
		$sql = "select id,cal_no,summary,create_time from {$this->baseTable} where vin_no='{$vin}' {$affix} order by modify_time DESC ;";
		$result = $APP_ADODB->Execute($sql);
		$list = array();
		while($result && !$result->EOF)
		{
			$row = array();
			$row['ID'] = $result->fields['id'];
			$row['CAL_NO'] = $result->fields['cal_no'];
			$row['SUMMARY'] = $result->fields['summary'];
			$row['MODIFY_TIME'] = $result->fields['create_time'];
			$list[] = $row;
			$result->MoveNext();
		}
		return $list;
	}

	/**
	 *	购置价查询后排序函数
	 * [multi_array_sort]
	 * $multi_array => 要传递的数组
	 * $sort_key    => 要传递的键值
	 * $sort 		=> SORT_ASC(升序排序)
	 */

	public function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC)
	{
		$key_array = array();
		if(is_array($multi_array))
		{
				foreach ($multi_array as $row_array)
				{
					if(array_key_exists($sort_key,$row_array))
					{
						$key_array[] = $row_array[$sort_key];
					}
					else
					{
						return false;
					}
				}
		}
		else
		{
						return false;
		}
		array_multisort($key_array,$sort,$multi_array);
		return $multi_array;
	}

	//险种代码转换
	private $kind_code = array(
					'200' => 'TVDI'        ,
					'202' => 'TVDI'        ,
					'500' => 'TWCDMVI'     ,
					'501' => 'TWCDMVI'     ,
					'600' => 'TTBLI'       ,
					'602' => 'TTBLI'       ,
					'701' => 'TCPLI_DRIVER'     ,
					'711' => 'TCPLI_DRIVER'     ,
					'702' =>  'TCPLI_PASSENGER'     ,
					'712' =>  'TCPLI_PASSENGER'     ,
					'310' => 'SLOI'        ,
					'311' => 'SLOI'        ,
					'210' => 'BSDI'        ,
					'211' => 'BSDI'        ,
					'232' => 'BGAI'        ,
					'253' => 'STSFS'        ,
					'261' => 'NIELI'       ,
					'441' => 'RDCCI'       ,
					'451' => 'MVLINFTPSI'       ,
					'461' => 'VWTLI'       ,
					'911' => 'TVDI_NDSI'   ,
					'930' => 'TVDI_NDSI'   ,
					'912' => 'TTBLI_NDSI'  ,
					'931' => 'TTBLI_NDSI'  ,
					'921' => 'TWCDMVI_NDSI',
					'932' => 'TWCDMVI_NDSI',
					'928' => 'TCPLI_DRIVER_NDSI',
					'933' => 'TCPLI_DRIVER_NDSI',
					'929' => 'TCPLI_PASSENGER_NDSI',
					'934' => 'TCPLI_PASSENGER_NDSI',
					'971' => 'BSDI_NDSI',
					'937' => 'BSDI_NDSI',
					'935' => 'SLOI_NDSI',
					'936' => 'NIELI_NDSI',
					'938' => 'VWTLI_NDSI',
					'938' => 'VWTLI_NDSI',
					'050200' => 'TVDI'        ,
					'050202' => 'TVDI'        ,
					'050500' => 'TWCDMVI'     ,
					'050501' => 'TWCDMVI'     ,
					'050600' => 'TTBLI'       ,
					'050602' => 'TTBLI'       ,
					'050701' => 'TCPLI_DRIVER'     ,
					'050711' => 'TCPLI_DRIVER'     ,
					'TCPLI_D' => 'TCPLI_DRIVER'     ,
					'050702' =>  'TCPLI_PASSENGER'     ,
					'050712' =>  'TCPLI_PASSENGER'     ,
					'TCPLI_P' => 'TCPLI_PASSENGER'     ,
					'050310' => 'SLOI'        ,
					'050311' => 'SLOI'        ,
					'050210' => 'BSDI'        ,
					'050211' => 'BSDI'        ,
					'050232' => 'BGAI'        ,
					'050253' => 'STSFS'        ,
					'050261' => 'NIELI'       ,
					'050441' => 'RDCCI'       ,
					'050451' => 'MVLINFTPSI'       ,
					'050461' => 'VWTLI'       ,
					'050911' => 'TVDI_NDSI'   ,
					'050930' => 'TVDI_NDSI'   ,
					'050912' => 'TTBLI_NDSI'  ,
					'050931' => 'TTBLI_NDSI'  ,
					'050921' => 'TWCDMVI_NDSI',
					'050932' => 'TWCDMVI_NDSI',
					'050928' => 'TCPLI_DRIVER_NDSI',
					'050933' => 'TCPLI_DRIVER_NDSI',
					'TCPLI_D_NDSI' => 'TCPLI_DRIVER_NDSI',
					'050929' => 'TCPLI_PASSENGER_NDSI',
					'050934' => 'TCPLI_PASSENGER_NDSI',
					'TCPLI_P_NDSI' => 'TCPLI_PASSENGER_NDSI',
					'050971' => 'BSDI_NDSI',
					'050937' => 'BSDI_NDSI',
					'050935' => 'SLOI_NDSI',
					'050936' => 'NIELI_NDSI',
					'050938' => 'VWTLI_NDSI',
					'050938' => 'VWTLI_NDSI',
					  );



	/**
	 * 获取保单算价参考
	 * 参数:
	 * @vin          必需。车架号
	 *
	 **/
	public function getPolicyRefer($vin)
	{
		require_once(_ROOT_DIR.'/include/InsDataExtraction/IDEService.class.php');
		$ide = new IDEService();
		$data = $ide->getPolicyRefer($vin);
		if(!is_array($data) || $data['code'] != 0) return false;
		$refer = $data['data'];
		if(is_array($refer))
		{
			foreach($refer['items'] as $key=>$val)
			{
				if(array_key_exists($val,$this->kind_code))
				{
					$refer['items'][$key] = $this->kind_code[$val];
				}
			}
		}
		return $refer;
	}

	public function getPolicyNumber($data){
		global $CURRENT_USER_ID;
		$api = isset($data['form']['OTHER']['PREMIUM_RATE_TABLE']) ? $data['form']['OTHER']['PREMIUM_RATE_TABLE'] : NULL;
		$pre_mvtalci_no  = "";
		$pre_business_no = "";
		if(is_file(_ROOT_DIR."/modules/InsuranceOrderCom/InsuranceOrderComModule.class.php"))
		{
			require_once(_ROOT_DIR."/modules/InsuranceOrderCom/InsuranceOrderComModule.class.php");
			$mod = new InsuranceOrderComModule();
			$queryWhere  = Array(Array("vin_no", "=", $data["form"]["AUTO"]["VIN_NO"], "AND", ""), Array("create_userid", "=", $CURRENT_USER_ID, "", ""));
			$filterWhere = Array();
			$orderby     = "create_time";
			$order       = "DESC";
			$userids     = NULL;
			$groupids    = NULL;
			$count       = 1;
	        $result = $mod->getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,0,$count);
	        if($result != false){
	        	$pre_mvtalci_no  = $result[0]["pre_mvtalci_no"];
	        	$pre_business_no = $result[0]["pre_business_no"];
	        }
		}
		$auto = Array(
			'LICENSE_NO'     => $data['form']['AUTO']['LICENSE_NO'],    //车牌号码
			'LICENSE_TYPE'   => $data['form']['AUTO']['LICENSE_TYPE'],  //号牌类别
			'OWNER'          => $data['form']['AUTO']['OWNER'],         //拥有人
			'MOBILE'		 => $data['form']['AUTO']['MOBILE'],        //电话号码
			'VIN_NO'         => $data['form']['AUTO']['VIN_NO'],        //车辆识别码
			'ENGINE_NO'      => $data['form']['AUTO']['ENGINE_NO'],     //发动机号码
			'MODEL'          => $data['form']['AUTO']['MODEL'],         //品牌型号
			'ENGINE'         => $data['form']['AUTO']['ENGINE'],        //排量
			'SEATS'          => $data['form']['AUTO']['SEATS'],         //核定载客
			'KERB_MASS'      => $data['form']['AUTO']['KERB_MASS'],     //整备质量
			'tonCount'       => $data['form']['AUTO']['TONNAGE'],       //核定载质量
			'MODEL_CODE'     => $data['form']['AUTO']['MODEL_CODE'],    //型号代码
			'BUYING_PRICE'   => $data['form']['AUTO']['BUYING_PRICE'],  //新车购置价
			'DISCOUNT_PRICE' => $data['form']['AUTO']['DISCOUNT_PRICE'], //折扣价
			'ENROLL_DATE'    => $data['form']['AUTO']['ENROLL_DATE'],   //注册日期
			'VEHICLE_TYPE'   => $data['form']['AUTO']['VEHICLE_TYPE'],  //车辆类别
			'USE_CHARACTER'  => $data['form']['AUTO']['USE_CHARACTER'], //使用性质
			'ORIGIN'         => $data['form']['AUTO']['ORIGIN'],        //产地
			'LicenseTypeDes' => $data['form']['AUTO']['VEHICLE_TYPE'],  //车辆种类
			'modelCode'      => $data['form']['AUTO']['MODEL_CODE'],    //车型编码
			'clauseType'     => 'F42',                                   //条款类型
			'IDENTIFY_NO'    => $data['form']['HOLDER']['HOLDER_IDENTIFY_NO'],    //身份证号码
		);
		$designated_driver = Array(
			Array(
				'DRIVER_NAME'        => $data['form']['HOLDER']['HOLDER'],                //驾驶人姓名
				'DRIVER_ADDRESS'     => $data['form']['HOLDER']['HOLDER_ADDRESS'],        //驾驶人地址
				'DRIVING_LICENCE_NO' => '',                                                //驾驶证号
				'DRIVER_ALLOW_DRIVE' => '',                                                //准驾代码
				'DRIVER_SEX'         => '',                                                //性别   1男性   2女性
				'DRIVER_AGE'         => '',                                                //年龄
				'DRIVING_YEARS'      => '',                                                //驾龄
				//'ID_CARD'            => $data['form']['HOLDER']['HOLDER_IDENTIFY_NO'],    //身份证号码
			),
		);
		$discount_vars = Array(
			'YEARS_OF_INSURANCE'     => '',       												//投保年度
			'CLAIM_RECORDS'          => '',       												//赔款记录
			'DRIVING_AREA'           => '11',     												//约定行驶区域   11:中国
			'AVERAGE_ANNUAL_MILEAGE' => '',       												//平均行驶里程
			'MULTIPLE_INSURANCE'     => '',       												//多险种优惠
		);

		$mvtalci = Array(
			'MVTALCI_NUMBER_INSURED' => $pre_mvtalci_no,			  							//交强险暂存单号
			'FLOATING_RATE'          => '',           		  									//交强险浮动标准
			'MVTALCI_AMOUNT'         => '122000',
			'MVTALCI_COUNT'          => '',
			'MVTALCI_PREMIUM'        => '',
			'TAX_PREMIUM'            => '',
			'MVTALCI_START_TIME'     => '',           											//交强险生效时间
			'MVTALCI_END_TIME'       => '',           											//交强险结束时间
		);
		$business = Array(
			'BUSINESS_NUMBER_INSURED' => $pre_business_no,								       //商业险暂存单号
			'DESIGNATED_DRIVER'       => $designated_driver, 								   //指定驾驶人,二维数组,结构见下
			'DISCOUNT_VARS'           => $discount_vars, 									   //折扣系数,结构见下
			'POLICY'                  => Array(), 											   //投保参数,结构见下
			'Total'                   => '',
			'BUSINESS_START_TIME'     => $data['form']['POLICY']['BUSINESS_START_TIME'],      //商业险生效时间
			'BUSINESS_END_TIME'       => $data['form']['POLICY']['BUSINESS_END_TIME'],        //商业险结束时间
		);

		if(isset($data['form']['MVTALCI_SELECT'])){
			$mvtalci['MVTALCI_START_TIME'] = $data['form']['POLICY']['MVTALCI_START_TIME'];
			$mvtalci['MVTALCI_END_TIME']   = $data['form']['POLICY']['MVTALCI_END_TIME'];
			$mvtalci['TAX_PREMIUM']        = $data['form']['POLICY']['TRAVEL_TAX_PREMIUM'];
			$mvtalci['MVTALCI_PREMIUM']    = $data['form']['POLICY']['MVTALCI_PREMIUM'];
			$mvtalci['MVTALCI_COUNT']      = $data['form']['POLICY']['MVTALCI_DISCOUNT'];
			$mvtalci['MVTALCI_AMOUNT']     = $data['form']['POLICY']['TOTAL_MVTALCI_PREMIUM'];
		}
		if(isset($data['form']['BUSINESS_ITEMS'])){
			$policy = $data['form']['POLICY'];
			foreach($data['form']['BUSINESS_ITEMS'] as $item){
				$policy['BUSINESS_ITEMS'][$item] = $data['form']['POLICY']["{$item}_PREMIUM"];
			}
			$business['POLICY'] = $policy;
		}
		$pcapi = $this->createPCObj($api);
		$response = $pcapi->documentary_cost($auto,$business,$mvtalci);
		return json_decode($response, true);
	}
	
	/**
	 * [encryptPwd 平安登录密码js加密]
	 * @param  [string] $sm2PubX  [加密参数]
	 * @param  [string] $sm2PubY  [加密参数]
	 * @param  [string] $password [密码]
	 * @return [string]           [加密后的密码]
	 */
	public function encryptPwd($sm2PubX,$sm2PubY,$password){
		  $passwordJsFile =_ROOT_DIR.'/public/js/pinganPassword.js';
	    if(!file_exists($passwordJsFile)){
	    	$this->error['errorMsg'] = '加密文件不存在';
	    	return false;
	    }
	    include($passwordJsFile);
  		$encryptPassword = "<script>doEncrypt('{$sm2PubX}', '{$sm2PubY}', '{$password}');</script>";
  		echo $encryptPassword; 	
	    return true;
	}

};



?>