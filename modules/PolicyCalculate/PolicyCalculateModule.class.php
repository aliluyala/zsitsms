<?php
class PolicyCalculateModule extends BaseModule
{
	public $baseTable = 'policy_draft';

	//模块描述
	public $describe = '保单算价';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'editView'     => '编辑',
			'createView'   => '新建',
			'copyView'     => '复制',
			'save'         => '保存',
			'delete'       => '删除',
			'import'       => '导入',
			'export'       => '导出',
			'batchDelete'  => '批量删除',
			'modifyFilter' => '编辑过滤',
			'sendPolicySMS'=> '发送短信',
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
			'form[OTHER][INSURANCE_COMPANY]'                         => Array('27','S',true,'',''),
			
			'form[INSURANT][HOLDER]'                                 => Array('7','S',true,'','',0,255,'410px'),
			'form[INSURANT][HOLDER_IDENTIFY_TYPE]'                   => Array('20','E',true,'',''),
			'form[INSURANT][HOLDER_ADDRESS]'                         => Array('7','S',true,'','',0,255,'410px'),
			'form[INSURANT][HOLDER_IDENTIFY_NO]'                     => Array('7','S',true,'','',0,255,'193px'),			
			
			
			'form[INSURANT][INSURANT]'                               => Array('7','S',true,'','',0,255,'410px'),
			'form[INSURANT][IDENTIFY_TYPE]'                          => Array('20','E',true,'',''),
			'form[INSURANT][ADDRESS]'                                => Array('7','S',true,'','',0,255,'410px'),
			'form[INSURANT][IDENTIFY_NO]'                            => Array('7','S',true,'','',0,255,'193px'),
			'form[AUTO][LICENSE_NO]'                                 => Array('7','S',true,'','',7,7,'160px','^[\u4e00-\u9fa5][a-zA-Z][a-zA-Z0-9]{5}$','车牌号码格式如：川A12345'),
			'form[AUTO][LICENSE_TYPE]'                               => Array('20','E',true,'',''),
			'form[AUTO][OWNER]'                                      => Array('7','S',true,'','',0,255,'193px'),
			'form[AUTO][VIN_NO]'                                     => Array('7','S',true,'','',1,17,'160px','^[a-zA-Z0-9]{1,17}$','车辆识别码由17位字母数字组成'),
			'form[AUTO][ENGINE_NO]'                                  => Array('7','S',true,'','',0,255,'160px'),
			'form[AUTO][MODEL]'                                      => Array('7','S',true,'','',0,255,'193px'),
			'form[AUTO][ENGINE]'                                     => Array('8','N',false,'','0',0,50000,'40px','ML',0),
			'form[AUTO][SEATS]'                                      => Array('8','N',false,'','5',1,100,'30px','人',0),
			'form[AUTO][KERB_MASS]'                                  => Array('8','N',false,'','0',0,100000,'40px','KG',0),
			'form[AUTO][MODEL_CODE]'                                 => Array('7','S',true,'','',0,255,'160px'),
			'form[AUTO][BUYING_PRICE]'                               => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[AUTO][ENROLL_DATE]'                                => Array('30','S',true,'',''),
			'form[AUTO][VEHICLE_TYPE]'                               => Array('20','E',true,'',''),
			'form[AUTO][USE_CHARACTER]'                              => Array('20','E',true,'',''),
			'form[AUTO][ORIGIN]'                                     => Array('20','E',true,'',''),
			
			'form[POLICY][TOTAL_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'50px','元',2),
			'form[POLICY][TOTAL_STANDARD_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'50px','元',2),			
			'form[POLICY][TOTAL_BUSINESS_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'50px','元',2),
			'form[POLICY][TOTAL_MVTALCI_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'50px','元',2),
			'form[POLICY][TOTAL_TRAVEL_TAX_PREMIUM]'                 => Array('8','N',false,'','0.00',0,1000000000,'50px','元',2),
			
			
			
			'form[POLICY][TRAVEL_TAX_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][MVTALCI_SELECT]'                           => Array('23','E',true,'',''),
			'form[POLICY][MVTALCI_PREMIUM]'                          => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][FLOATING_RATE]'                            => Array('8','N',false,'','0',-70,70,'40px','%',0),
			'form[POLICY][MVTALCI_START_TIME]'                       => Array('31','DT',true,'',''),
			'form[POLICY][MVTALCI_END_TIME]'                         => Array('31','DT',true,'',''),
			'form[POLICY][BUSINESS_PREMIUM]'                         => Array('8','N',false,'','0.00',0,1000000000,'70px','元',2),
			'form[POLICY][BUSINESS_DISCOUNT]'                        => Array('8','N',false,'','1.000',0,2,'70px','',3),
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
			'form[POLICY][MULTIPLE_INSURANCE]'                       => Array('8','N',false,'','1.00',0.95,1,'70px','',2),
			'form[POLICY][BUSINESS_DISCOUNT_SHWY]'                   => Array('8','N',false,'','1.000',0,2,'70px','',3),
			
			'form[POLICY][TVDI_INSURANCE_AMOUNT]'                    => Array('8','N',false,'','0',0,1000000000,'70px','',0),
			'form[POLICY][DOC_AMOUNT]'                               => Array('20','E',true,'',''),
			'form[POLICY][TVDI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TVDI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_INSURANCE_AMOUNT]'                   => Array('20','E',true,'',''),
			'form[POLICY][TTBLI_INSURANCE_AMOUNT_EXT]'               => Array('8','N',false,'','120',100,800,'40px','万',0),
			'form[POLICY][TTBLI_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TTBLI_DISCOUNT_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TWCDMVI_INSURANCE_AMOUNT]'                 => Array('8','N',false,'','0',0,1000000000,'70px','',0),
			'form[POLICY][TWCDMVI_PREMIUM]'                          => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TWCDMVI_DISCOUNT_PREMIUM]'                 => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_INSURANCE_DRIVER_AMOUNT]'            => Array('8','N',false,'','0',0,1000000000,'70px','万',0),
			'form[POLICY][TCPLI_DRIVER_PREMIUM]'                     => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_DRIVER_DISCOUNT_PREMIUM]'            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_INSURANCE_PASSENGER_AMOUNT]'         => Array('8','N',false,'','0',0,1000000000,'70px','万',0),
			'form[POLICY][TCPLI_PASSENGER_PREMIUM]'                  => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][TCPLI_PASSENGER_COUNT]'                    => Array('8','N',false,'','0',0,100,'30px','',0),
			'form[POLICY][TCPLI_PASSENGER_DISCOUNT_PREMIUM]'         => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BSDI_INSURANCE_AMOUNT]'                    => Array('20','E',false,'',''),
			'form[POLICY][BSDI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BSDI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_INSURANCE_AMOUNT]'                    => Array('8','N',false,'','0',0,1000000000,'70px','',0),
			'form[POLICY][SLOI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][GLASS_ORIGIN]'                             => Array('20','E',true,'',''),
			'form[POLICY][BGAI_PREMIUM]'                             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][BGAI_DISCOUNT_PREMIUM]'                    => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_INSURANCE_AMOUNT]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_PREMIUM]'                            => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][NIELI_DISCOUNT_PREMIUM]'                   => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][STSFS_RATE]'                               => Array('8','N',false,'','0.0',0,200,'40px','%',1),
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
			'form[POLICY][SLOI_NDSI_PREMIUM]'                       => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][SLOI_NDSI_DISCOUNT_PREMIUM]'              => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			
			'form[POLICY][WADING_INSURANCE_AMOUNT]'                  => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][WADING_PREMIUM]'                           => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][WADING_DISCOUNT_PREMIUM]'                  => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][WADING_NDSI_PREMIUM]'                      => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
			'form[POLICY][WADING_NDSI_DISCOUNT_PREMIUM]'             => Array('8','N',false,'','0.00',0,1000000000,'70px','',2),
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
			
			'form[POLICY][BUSINESS]'			                     => Array('5','S',false,'',''),			
			'form[OTHER][SHIYEICAO_PRODUCT]'                         => Array('27','S',false,'',''),
			'form[OTHER][GIFT]'                                      => Array('9','S',false,'',''),
			'form[OTHER][REMARKS]'                                   => Array('9','S',false,'',''),
			
			
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
		'form[POLICY][DRIVER_ALLOW_DRIVE1]' => array('A1','A2','A3','B1','B2','C1','C2','C3','C4','C5','D','E','F','M','N','P'),
		'form[POLICY][DRIVER_ALLOW_DRIVE2]' => array('A1','A2','A3','B1','B2','C1','C2','C3','C4','C5','D','E','F','M','N','P'),		
		'form[POLICY][DRIVER_ALLOW_DRIVE3]' => array('A1','A2','A3','B1','B2','C1','C2','C3','C4','C5','D','E','F','M','N','P'),					
							
							
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
							"100+",
							),
		'form[POLICY][BSDI_INSURANCE_AMOUNT]'=>array('2000','5000','10000','20000'),
		'form[POLICY][GLASS_ORIGIN]' => array("DOMESTIC","IMPORTED","SPECIAL"),		
		
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

};



?>