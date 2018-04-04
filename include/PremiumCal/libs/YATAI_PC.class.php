<?php
/**
 * 项目:           车险保费在线计算接口
 * 文件名:         PICCKHYXSC_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          Liang Yulin
 * 版本：          1.0.0
 *
 * 亚太保险公司系统算价接口
 *
 **/
session_start();
class YATAI_PC
{

	const formFile = 'Calculate.tpl';
	const company  = 'API';
    private $error    = "";//设置错误信息成员属性（默认值）
	private $setItems = array(
		'username' => '授权登录名',
		'password' => '授权登录名密码',
		'ip'       => '授权IP地址',
	);


		function __construct($config)
		{
	          $user = '';
	          if(array_key_exists('username',$config) &&  $config['username']!="")
	          {
	               $this->arr['user'] = $config['username'];
	          }


	          $password = '';
	          if(array_key_exists('password',$config) && $config['password']!="")
	          {
	               $this->arr['password'] = $config['password'];
	          }


	          $ip = '';

	          if(array_key_exists('ip',$config) && $config['ip']!="")
	          {
	               $this->arr['ip']=$config['ip'];
	          }



	      
	        $this->WSDL_URL="http://cextplat.minanins.com:9013/webservices/wsCarQuoteService?wsdl";

		}

	/**
	 * @return [返回设置项目]
	 */
	public function getSetItems()
	{
		return $this->setItems;
	}

	
	/**
	 * @return [返回表单模板文件名]
	 */
	public function getFormFile()
	{
		return self::formFile;
	}


	/**
	 * [getLastError 返回最后一次错误信息]
	 * @AuthorHTL
	 * @DateTime  2016-03-29T15:24:59+0800
	 * @return    [error]  返回保存的错误信息
	 */
	public function getLastError()
	{
		return $this->error;
	}

		/**
		 * [queryBuyingPrice 查询购置价]
		 * @param  array  $info [查询条件]
		 * @return [type]       [返回数组]
		 */
		public function queryBuyingPrice($info=array())
		{
			if(!isset($info['model']) && $info['model']=="")
			{
				return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
			}

			try {

					$model="<BaseInfo><ModelCName>{$info['model']}</ModelCName></BaseInfo>";
					$str=self::XML($RequestType="Q06",$model);
					$array= self::pro_XML($str);
				    $page = "1";
				    if(!empty($info['page']))
				    {
				        $page = $info['page'];
				    }
				    $rows = 10;
				    if(!isset($array['Body']['CarModels']['CarModel']))
				    {
				    	return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
				    }
				    	

				    $count= count($array['Body']['CarModels']['CarModel']);
				 	$page_result= self::page_array($rows,$page,$array['Body']['CarModels']['CarModel'],0);
				 	foreach($page_result as $row)
					{

						$line = array();
						$line['vehicleId']             = $row['ModelCode'];
						$line['vehicleName']           = $row['ModelCName'];
		                //$line['vehicleAlias']          = $row['vehicleAlias'];
						//$line['vehicleMaker']          = $row['vehicleMaker'];
						$line['vehicleWeight']         = $row['FullWeight']/1000;
						$line['vehicleDisplacement']   = $row['ExhaustScale']/1000;
						//$line['vehicleTonnage']   	   = $row['vehicleTonnage'];
						$line['vehiclePrice']          = round($row['PurchasePriceNotTax']);
						$line['szxhTaxedPrice']        = round($row['PurchasePrice']);
						$line['xhKindPrice']           = 0;
						$line['nXhKindpriceWithouttax']= 0;
						$line['vehicleSeat']           = $row['SeatCount'];
						$line['vehicleYear']           = $row['CarYear'];
						$retdata['rows'][] = $line;
					}
					
				    $retdata['total']= ceil($count/$rows);
				    $retdata['page']= intval($page);
				    $retdata['records']= $count;
					return $retdata;
			   
				} catch (SOAPFault $e) {
				    $this->error['errorMsg']=$e;
				    return false;
				}

		}	

		 /**
     * [deviceDepreciation 设备折旧价计算]
     * @AuthorHTL
     * @DateTime  2016-05-26T16:16:19+0800
     * @param     array                    $info [参数数组]
     * @return    [type]                         [成功返回数组，失败返回false]
     */
     public function deviceDepreciation($info=array())
     {

          if(empty($info) || !isset($info)){
               $this->error="参数信息不能为空";
               return false;
          }

          foreach($info['DEVICE_LIST'] as $k =>$v)
          {
               if(!isset($v['NAME']))
               {
                    $this->error="设备名称不能为空";
                    return false;
               }

               if(!isset($v['BUYING_PRICE']))
               {
                    $this->error="新购价格不能为空";
                    return false;
               }

               if(!isset($v['COUNT']))
               {
                    $this->error="数量不能为空";
                    return false;
               }

               if(!isset($v['BUYING_DATE']))
               {
                    $this->error="购置日期不能空";
                    return false;
               }

               if(!isset($info['BUSINESS_START_TIME']))
               {
                    $this->error="商业险日期不能为空";
                    return false;
               }

               if(!isset($info['VEHICLE_TYPE']))
               {
                    $this->error="车辆种类不能为空";
                    return false;
               }


               if(!isset($info['USE_CHARACTER']))
               {
                    $this->error="使用性质不能为空";
                    return false;
               }




                    $ENROLLhour = date('Y',strtotime($v['BUYING_DATE']))*12+(date('m',strtotime($v['BUYING_DATE'])));
                    $START_TIMEhour = date('Y',strtotime($info['BUSINESS_START_TIME']))*12+(date('m',strtotime($info['BUSINESS_START_TIME'])));
                    $Month=$START_TIMEhour-$ENROLLhour;//折扣月份

                     if(date('d',strtotime($info['BUSINESS_START_TIME']))<date('d',strtotime($v['BUYING_DATE'])))
                    {
                         $Month--;//比较日期
                    }
                    if($Month < 0)
                     {
                              $Month = 0;//如果相差为负数时，设置为0，否则为引起险别重新计算保额错误

                     }

                    if($info['USE_CHARACTER']=="NON_OPERATING_PRIVATE" && $info['VEHICLE_TYPE']=="PASSENGER_CAR") //判断是否是家用车
                     {
                         $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.006;

                     }
                     else if($info['VEHICLE_TYPE']=="TRUCK")//货车类型
                     {

                         if($info['USE_CHARACTER']== 'OPERATING_TRUCK')
                         {

                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;

                         }

                         else if($info['USE_CHARACTER']== 'NON_OPERATING_TRUCK')
                         {

                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                         }
                         else if($info['USE_CHARACTER']== 'NON_OPERATING_LOW_SPEED_TRUCK')
                         {
                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;
                         }
                         else if($info['USE_CHARACTER']== 'OPERATING_LOW_SPEED_TRUCK')
                         {
                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.014;

                         }

                     }
                     else if($v['VEHICLE_TYPE']=="PASSENGER_CAR")//客车类型
                     {

                        if($v['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE" || $v['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
                         {
                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.006;

                         }
                         else if($v['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
                         {

                              $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;

                         }
                         else if($v['USE_CHARACTER']=="OPERATING_CITY_BUS" || $v['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
                         {

                              $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                         }


                     }

                     else if($v['VEHICLE_TYPE']=='THREE_WHEELED')
                              {
                                   $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;
                              }

                    $data[$k]['BUYING_DATE']=$v['BUYING_DATE'];
                    $data[$k]['BUYING_PRICE']=$v['BUYING_PRICE'];
                    $data[$k]['COUNT']=$v['COUNT'];
                    $data[$k]['DEPRECIATION']=$BUYING_PRICE;//$BUYING_PRICE*$v['COUNT']设备小计
                    $data[$k]['NAME']=$v['NAME'];

          }

                    return $data;

     }
     	/**
     	 * [login_time 格式化当前时间戳]
     	 * @return [type] [description]
     	 */
		private function login_time()
		{
			$time=date("Y-m-d H:i:s");
			return $time;

		}

		/**
		 * [XML XML组装参数信息]
		 * @param string  $RequestType [请求类型]
		 * @param string  $str         [请求内容]
		 */
		private function XML($RequestType="",$str="")
		{

				$XML='<?xml version="1.0" encoding="GBK" standalone="yes"?>
				<Packet type="REQUEST" version="1.0">
				    <Head>
				   <!-- 请求类型 -->
						<RequestType>'.$RequestType.'</RequestType>
						<!-- 请求服务时间 -->
						<FlowInTime>'.$this->login_time().'</FlowInTime>
						<!-- 访问用户名 -->
						<RequesterCode>'.$this->arr['user'].'</RequesterCode>
						<!-- 访问密码 -->
						<PassWord>'.$this->arr['password'].'</PassWord>
						<!-- 调用者代码 -->
						<IP>'.$this->arr['ip'].'</IP>
					</Head>
				 
				    <Body>'.$str.'</Body>
				</Packet>';
				return $XML;

		}

		/**
		 * [page_array 数组分页]
		 * @param  [type] $count [每页显示个数]
		 * @param  [type] $page  [当前第几页]
		 * @param  [type] $array [查询数组]
		 * @param  [type] $order [排序规则]
		 * @return [type]        [返回查询数组]
		 */
		private function page_array($count,$page,$array,$order)
		{
			  global $countpage; #定全局变量
			    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面 
			      $start=($page-1)*$count; #计算每次分页的开始位置
			  if($order==1)
			  {
			   	  $array=array_reverse($array);
			  }  
				  $totals=count($array); 
				  $countpage=ceil($totals/$count); #计算总页面数
				  $pagedata=array();
				  $pagedata=array_slice($array,$start,$count);
			  	  return $pagedata; #返回查询数据
		}

		/**
      * [depreciation 车辆折旧价计算]
      * @AuthorHTL
      * @DateTime  2016-05-26T16:15:54+0800
      * @param     [type]                   $info [参数数组]
      * @return    [type]                         [成功返回数组，失败返回false]
      */
     public function depreciation($info)
     {

          if($info['BUYING_PRICE']!="" && $info['ENROLL_DATE']!="" && $info['BUSINESS_START_TIME']!="")
          {
                    $ENROLLhour = date('Y',strtotime($info['ENROLL_DATE']))*12+(date('m',strtotime($info['ENROLL_DATE'])));
                    $START_TIMEhour = date('Y',strtotime($info['BUSINESS_START_TIME']))*12+(date('m',strtotime($info['BUSINESS_START_TIME'])));
                    $Month=$START_TIMEhour-$ENROLLhour;


                    if(date('d',strtotime($info['BUSINESS_START_TIME']))<date('d',strtotime($info['ENROLL_DATE'])))
                    {
                         $Month--;//比较日期
                    }
                    if($Month < 0)
                     {
                              $Month = 0;//如果相差为负数时，设置为0，否则为引起险别重新计算保额错误

                     }
                     if($info['USE_CHARACTER']=="NON_OPERATING_PRIVATE" && $info['VEHICLE_TYPE']=="PASSENGER_CAR") //判断是否是家用车
                     {
                         return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.006;

                     }
                     else if($info['VEHICLE_TYPE']=="TRUCK")//货车类型
                     {

                         if($info['USE_CHARACTER']== 'OPERATING_TRUCK')
                         {

                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.009;

                         }

                         else if($info['USE_CHARACTER']== 'NON_OPERATING_TRUCK')
                         {

                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.009;
                         }
                         else if($info['USE_CHARACTER']== 'NON_OPERATING_LOW_SPEED_TRUCK')
                         {
                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011;
                         }
                         else if($info['USE_CHARACTER']== 'OPERATING_LOW_SPEED_TRUCK')
                         {
                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.014;

                         }

                     }
                     else if($info['VEHICLE_TYPE']=="PASSENGER_CAR")//客车类型
                     {

                        if($info['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE" || $info['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
                         {
                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.006;

                         }
                         else if($info['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
                         {

                              return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011;

                         }
                         else if($info['USE_CHARACTER']=="OPERATING_CITY_BUS" || $info['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
                         {

                              return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.009;
                         }


                     }

                     else if($info['VEHICLE_TYPE']=='THREE_WHEELED')
                              {
                                   return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011;
                              }
                     return false;

          }


     }
     	/**
     	 * [premium 查询报价]
     	 * @param  array  $auto     [车辆信息]
     	 * @param  array  $business [商业险信息]
     	 * @param  array  $mvtalci  [交强险信息]
     	 * @return [type]           [返回查询结果]
     	 */
		public  function premium($auto=array(),$business=array(),$mvtalci=array())
		{

        $str="";
        $str.=self::car($auto,$business,$mvtalci);

        if(isset($business['POLICY']['BUSINESS_ITEMS']) && !empty($business['POLICY']['BUSINESS_ITEMS']))
        {
          $str.=self::business($business);
        }

        if(!empty($mvtalci))
        {
          $str.=self::mvtalci($mvtalci);
        }

				$XML= self::XML("Q01",$str);
        try{

					 //设置算价返回默认值
	                $results=array();
	                $results['MESSAGE']                      = '';
	                $results['MVTALCI'] = array();
	                $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= '0.00';
	                $results['MVTALCI']['MVTALCI_PREMIUM']   = '0.00';
	                $results['MVTALCI']['MVTALCI_DISCOUNT']  = '1.000';
	                $results['MVTALCI']['MVTALCI_START_TIME']= '';
	                $results['MVTALCI']['MVTALCI_END_TIME']  = '';
	                 

	                $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']="0.0";//round($resen['biPremium']*$resen['biDiscount'],2); //商业险扣后保费合计
                  $results['BUSINESS']['BUSINESS_DISCOUNT']="0.0";//$resen['biDiscount'];         //商业险折扣
                  $results['BUSINESS']['BUSINESS_PREMIUM']="0.0";//$resen['biPremium'];          //商业险标准保费合计
                  $results['BUSINESS']['BUSINESS_START_TIME'] = $business['BUSINESS_START_TIME'];       //商业险生效时间
                  $results['BUSINESS']['BUSINESS_END_TIME'] = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($business['BUSINESS_START_TIME'])));//商业险结束时间
                                  /*******************投保项目保费二维数组********************/
						      $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']                 = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']              = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']                = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']         = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']      = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']                 = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']                 = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']                = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']                = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']                 = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']                = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']                = '0.00';
                  $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']           = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']            = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']           = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']         = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']    = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM'] = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']            = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']            = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']           = '0.00';
		              $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']           = '0.00';


				

						$premium= self::pro_XML($XML);
					    if(strpos($premium['Head']['ErrorMessage'],"报价成功"))
					    {

                $results['MESSAGE']=$premium['Head']['ErrorMessage']."<br />报价单号:".$premium['Body']['CarQuoteMain']['QuoteNo']; //报价单号
                $_SESSION['API_QuoteNo'] = $premium['Body']['CarQuoteMain']['QuoteNo'];
                      session_write_close();

					    	if(!empty($mvtalci))
			          {
				                 /*******************交强险********************/
				                 $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= $premium['Body']['CarQuoteMain']['ThisPayTax'];      //车船税
				                 $results['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];       //交强险生效时间
				                 $results['MVTALCI']['MVTALCI_END_TIME']  = $mvtalci['MVTALCI_END_TIME'];       //交强险结束时间
                         $results['MVTALCI']['MVTALCI_DISCOUNT']  =  $premium['Body']['CarQuoteMain']['Discount']/100;//交强险折扣
                         $results['MVTALCI']['MVTALCI_PREMIUM']   =  $premium['Body']['CarQuoteMain']['SumPremium'];
			          }

                if(isset($business['POLICY']['BUSINESS_ITEMS']) && !empty($business['POLICY']['BUSINESS_ITEMS']))
                {
                        
	                 		$results['BUSINESS']['BUSINESS_DISCOUNT']=  $premium['Body']['CarQuoteMain']['Discount']/100;         //商业险折扣
	                 		$results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']=round($premium['Body']['CarQuoteMain']['SumPremiumb']*$results['BUSINESS']['BUSINESS_DISCOUNT'],2); //商业险扣后保费合计
                         	
                         	$results['BUSINESS']['BUSINESS_PREMIUM']=   $premium['Body']['CarQuoteMain']['SumPremiumb'];          //商业险标准保费合计
	           	
	                 		foreach($premium['Body']['CarQuoteItemKindList']['CarQuoteItemKind'] as $k =>$v)
	                 		{
	                 			
	                 			switch ($v['KindCode']) 
                        {

	                 				case 'BZ':
	                 					$results['MVTALCI']['MVTALCI_DISCOUNT']  				   =  $v['Discount']/100;          //交强险折扣
	                 					$results['MVTALCI']['MVTALCI_PREMIUM']                     =  $v['Premium'];
	                 					break;
	                 				case 'A':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']  = $v['Premium'];
	                 					break;	
	                 				case 'M11':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'B':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'M12':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'G':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']  = $v['Premium'];
	                 					break;	
	                 				case 'M15':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;	
	                 				case 'D1':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'M13':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;	

	                 				case 'D2':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']  = $v['Premium'];
	                 					break;		
	                 				case 'M14':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;

	                 				case 'Z':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']  = $v['Premium'];
	                 					break;	
	                 				case 'M21':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;	

	                 				case 'EW':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'M23':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']  = $v['Premium'];
	                 					break;

	                 				case 'ZC':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'RC':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				case 'TS':
	                 					$results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']  = $v['Premium'];
	                 					break;
	                 				
	                 			}
	                 			
	                 				
	                 		}	
	                 			
                  }      
	                 	return $results;	
					    }
					    else
					    {
					    	$this->error['errorMsg']=$premium['Head']['ErrorMessage'];
					    	return false;
					    }	


					}
				catch (SOAPFault $e) {
				   $this->error['errorMsg']=$e;
				   return false;
				}




}	

	/**
     * [documentary_cost 通过不同代理商计算跟单费用以及生成暂存单号]
     * @AuthorHTL
     * @DateTime  2016-11-16T11:28:03+0800
     * 参数:
     * @auto            必需,数组
     *    数组结构如下:
     *    array(
     *      'VEHICLE_TYPE' =>'' , //车辆类别
     *      'USE_CHARACTER'=>'' , //使用性质
     *     )
     *----------------------------------------------------------------------------
     * @business        必需,数组。
     *     数组结构如下:
     *     array(
     *       'Policy'             => array(
     *                      'BUSINESS_ITEMS'=>array(
     *                              'MVTALCI'  =>$prieum,
     *                              'TVDI' =>$prieum,
     *                          ......
     *                      )//投保险种及保费
     *
     *              ),
     *      'Total' =>array(
     *                  'discount'=>'',         //总折扣
     *                  'sumPremium'=>'',       //含税总保费
     *       )
     *       ''
     *      )
     * @return    [type]                   [description]
     */
		public function documentary_cost($auto=array(),$business=array(),$mvtalci=array())
		{

					if($_SESSION['API_QuoteNo']=="")
					{
						$this->error['errorMsg']="请重新计算保费";
						return false;
					}

					if($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS']=="")
					{
						$this->error['errorMsg']="客户地址不能为空";
						return false;
					}

					if($auto['OWNER']=="")
					{	
						$this->error['errorMsg']="关系人名称不能为空";
						return false;
					}

				
					if($auto['IDENTIFY_NO']=="")
					{
						$this->error['errorMsg']="关系人身份证号码不能为空";
						return false;
					}	

					if($auto['MOBILE']=="")
					{
						$this->error['errorMsg']="关系人手机号码不能为空";
						return false;
					}	

					$XML='<CarQuoteTransProposalReq>
							<QuoteNo>'.$_SESSION['API_QuoteNo'].'</QuoteNo>
						 </CarQuoteTransProposalReq>
						 <CarQuoteRelatedPartyList>
							<CarQuoteRelatedParty>
								<SerialNo>0</SerialNo>
								<InsuredFlag>1</InsuredFlag>
								<InsuredType>1</InsuredType>
								<PostCode>250000</PostCode>
								<InsuredAddress>'.$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'].'</InsuredAddress>
								<InsuredName>'.$auto['OWNER'].'</InsuredName>
								<IdentifyNumber>'.$auto['IDENTIFY_NO'].'</IdentifyNumber>
								<MobilePhone>'.$auto['MOBILE'].'</MobilePhone>
								<Sex>2</Sex>
							</CarQuoteRelatedParty>
						 </CarQuoteRelatedPartyList>
						<CarQuoteDistribution>
						<Name>'.$auto['OWNER'].'</Name>
						<Address>'.$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'].'</Address>
						<PhoneNumber>'.$auto['MOBILE'].'</PhoneNumber>
						</CarQuoteDistribution>';
					$head= self::XML("Q04",$XML);
					$pro_xml=self::pro_XML($head);
					$result_json=array();
					if(empty($pro_xml['Head']['ErrorMessage']))
					{
						foreach($pro_xml['Body']['CarQuoteTransProposalRspList']['CarQuoteTransProposalRsp'] as $k=>$v)
						{
							if($v['RiskCode']=="0806")
							{
								$result_json['TDZA']=$v['ProposalNo'];
							}

							if($v['RiskCode']=="0812")
							{
								$result_json['TDAA']=$v['ProposalNo'];
							}
						}
						$result_json['state']="0";
                    	return json_encode($result_json);
					}
					else
					{
						$this->error['errorMsg']=$pro_xml['Head']['ErrorMessage'];
		                $this->error['state']="1";
		                return json_encode($this->error);
					}	
					


		}


		/**
		 * [pro_XML 实例化SOAP对象并解析XML成JSON对象]
		 * @param  string $XML [description]
		 * @return [type]      [description]
		 */
		private function pro_XML($XML="")
		{			

					$client = new SoapClient($this->WSDL_URL);
				    $array['requestXml']=$XML;
				    $result = $client->acceptCarQuote($array);
				    $re= $result->return;
				    $premium= self::xml_to_json($re);
				    return $premium;
		}


		

		
		private function xml_to_json($results)
		{
					$result= str_replace("GBK", "UTF-8", $results);
				    $object= simplexml_load_string($result);
				    $array= json_decode(json_encode($object),true);
				    return $array;
		}

		
		private function car($auto=array(),$business=array(),$mvtalci=array())
		{
      $xml="";
      if(!empty($mvtalci))
      {
        $mvtalci_start= explode(" ",$mvtalci['MVTALCI_START_TIME']);
        $xml.="<StartDateCI>{$mvtalci_start[0]}</StartDateCI>";
        $mvtalci_end= explode(" ",$mvtalci['MVTALCI_END_TIME']);
        $xml.="<EndDateCI>{$mvtalci_end[0]}</EndDateCI>";
      }

			if(isset($business['POLICY']['BUSINESS_ITEMS']) && !empty($business['POLICY']['BUSINESS_ITEMS']))
      {
          $business_start= explode(" ",$business['BUSINESS_START_TIME']);
          $xml.="<StartDateBI>{$business_start[0]}</StartDateBI>";
          $business_end= explode(" ",$business['BUSINESS_END_TIME']);
          $xml.="<EndDateBI>{$business_end[0]}</EndDateBI>";
      }
			
			$str="<CarQuoteItemMotor>
				<LicenseNo>{$auto['LICENSE_NO']}</LicenseNo>
				<EngineNo>{$auto['ENGINE_NO']}</EngineNo>
				<FrameNo>{$auto['VIN_NO']}</FrameNo>
				<EnrollDate>{$auto['ENROLL_DATE']}</EnrollDate>
				{$xml}
				<ModelCode>{$auto['MODEL_CODE']}</ModelCode>
				<BrandName>{$auto['MODEL']}</BrandName>
				<PurchasePrice>{$auto['BUYING_PRICE']}</PurchasePrice>
				<GlassType>2</GlassType>
				<ChgOwnerFlag>0</ChgOwnerFlag>
				<CarOwner>{$auto['OWNER']}</CarOwner>
				<OwnerIdentifyNumber>{$auto['IDENTIFY_NO']}</OwnerIdentifyNumber>
				<RunAreaCode>370100</RunAreaCode>
				</CarQuoteItemMotor>";
				return $str;

		


		}
		
		private function mvtalci($mvtalci)
		{
			$mvtalci_XML='<CarQuoteItemKindCIList>
					<CarQuoteItemKindCI>
					<KindCode>BZ</KindCode>
					<KindName>交强险</KindName>
					<ItemKindNo>12</ItemKindNo>
					</CarQuoteItemKindCI>
					</CarQuoteItemKindCIList>';
			return $mvtalci_XML;		
			

		}


		private function business($business)
		{
			$items_xml="";
			$i=0;
			if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="5")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="50000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="10")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="100000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="15")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="150000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="20")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="200000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="30")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="300000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="50")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="500000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="100")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="1000000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="150")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="1500000";
			}
			else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="200")
			{
			  $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="2000000";
			}


			foreach ($business['POLICY']['BUSINESS_ITEMS'] as $key => $value) {
				switch ($value) {

					case 'TVDI':
				$items_xml.='<CarQuoteItemKindBI>
				<KindCode>A</KindCode>
				<KindName>车辆损失险</KindName>
				<ItemKindNo>'.++$i.'</ItemKindNo>
				<SumInsured>'.$business['POLICY']['TVDI_INSURANCE_AMOUNT'].'</SumInsured>
				<UnitInsured>0.0</UnitInsured>
				<Quantity>0</Quantity>
				<RelatedInd>1</RelatedInd>
				<KindInd>1</KindInd>
				</CarQuoteItemKindBI>';
						break;

					case 'TVDI_NDSI':
					$items_xml.='<CarQuoteItemKindBI>
				<KindCode>M11</KindCode>
				<KindName>车辆损失险不计免赔</KindName>
				<ItemKindNo>'.++$i.'</ItemKindNo>
				<SumInsured>0.0</SumInsured>
				<UnitInsured>0.0</UnitInsured>
				<RelatedInd>0</RelatedInd>
				<KindInd>2</KindInd>
				</CarQuoteItemKindBI>';
						break;

					case 'TTBLI':	
					$items_xml.='<CarQuoteItemKindBI>
				<KindCode>B</KindCode>
				<KindName>第三者责任险</KindName>
				<ItemKindNo>'.++$i.'</ItemKindNo>
				<SumInsured>'.$business["POLICY"]["TTBLI_INSURANCE_AMOUNT"].'</SumInsured>
				<UnitInsured>0.0</UnitInsured>
				<Quantity>0</Quantity>
				<RelatedInd>1</RelatedInd>
				<KindInd>1</KindInd>
				</CarQuoteItemKindBI>';	
						break;

				case 'TTBLI_NDSI':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M12</KindCode>
					<KindName>第三者责任险不计免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;


				case 'TWCDMVI':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>G</KindCode>
					<KindName>全车盗抢险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>'.$business["POLICY"]["TWCDMVI_INSURANCE_AMOUNT"].'</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>1</RelatedInd>
					<KindInd>1</KindInd>
					</CarQuoteItemKindBI>';	
						break;


				case 'TWCDMVI_NDSI':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M15</KindCode>
					<KindName>全车盗抢险不计免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;	


				case 'TCPLI_DRIVER':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>D1</KindCode>
					<KindName>司机座位责任险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>'.round($business["POLICY"]["TCPLI_INSURANCE_DRIVER_AMOUNT"],2).'</SumInsured>
					<UnitInsured>'.round($business["POLICY"]["TCPLI_INSURANCE_DRIVER_AMOUNT"],2).'</UnitInsured>
					<Quantity>1</Quantity>
					<RelatedInd>1</RelatedInd>
					<KindInd>1</KindInd>
					</CarQuoteItemKindBI>';	
						break;		


				case 'TCPLI_DRIVER_NDSI':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M13</KindCode>
					<KindName>司机座位责任险不计免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;	


					case 'TCPLI_PASSENGER':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>D2</KindCode>
					<KindName>乘客座位责任险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>'.$business["POLICY"]["TCPLI_INSURANCE_PASSENGER_AMOUNT"]*$business["POLICY"]["TCPLI_PASSENGER_COUNT"].'</SumInsured>
					<UnitInsured>'.$business["POLICY"]["TCPLI_INSURANCE_PASSENGER_AMOUNT"].'</UnitInsured>
					<Quantity>'.$business["POLICY"]["TCPLI_PASSENGER_COUNT"].'</Quantity>
					<RelatedInd>1</RelatedInd>
					<KindInd>1</KindInd>
					</CarQuoteItemKindBI>';	
						break;		


					case 'TCPLI_PASSENGER_NDSI':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M14</KindCode>
					<KindName>乘客座位责任险不计免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;


					case 'BSDI':	
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>L</KindCode>
					<KindName>车身划痕险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>'.$business["POLICY"]["BSDI_INSURANCE_AMOUNT"].'</SumInsured>
					<UnitInsured>'.$business["POLICY"]["BSDI_INSURANCE_AMOUNT"].'</UnitInsured>
					<Quantity>1</Quantity>
					<RelatedInd>1</RelatedInd>
					<KindInd>1</KindInd>
					</CarQuoteItemKindBI>';	
						break;


					case 'BSDI_NDSI':		
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M22</KindCode>
					<KindName>车身划痕险不计免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>1</Quantity>
					<RelatedInd>1</RelatedInd>
					<KindInd>1</KindInd>
					</CarQuoteItemKindBI>';	
						break;

					case 'SLOI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>Z</KindCode>
					<KindName>自燃损失险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;

					case 'SLOI_NDSI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M21</KindCode>
					<KindName>自燃损失险不计免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;


					case 'BGAI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>F</KindCode>
					<KindName>玻璃单独破碎险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;


					case 'NIELI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>X</KindCode>
					<KindName>新增设备损失险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>'.$business["POLICY"]["NIELI_INSURANCE_AMOUNT"].'</SumInsured>
					<UnitInsured>'.$business["POLICY"]["NIELI_INSURANCE_AMOUNT"].'</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;


					case 'VWTLI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>EW</KindCode>
					<KindName>涉水行驶损失险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;	


					case 'VWTLI_NDSI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>M23</KindCode>
					<KindName>涉水行驶损失险不及免赔</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;	

					


					case 'STSFS':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>ZC</KindCode>
					<KindName>指定专修厂特约条款</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;


					case 'RDCCI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>RC</KindCode>
					<KindName>修理期间费用补偿险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>'.$business["POLICY"]["RDCCI_INSURANCE_UNIT"]*$business["POLICY"]["RDCCI_INSURANCE_QUANTITY"].'</SumInsured>
					<UnitInsured>'.$business["POLICY"]["RDCCI_INSURANCE_UNIT"]*$business["POLICY"]["RDCCI_INSURANCE_QUANTITY"].'</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;	


					case 'MVLINFTPSI':
					$items_xml.='<CarQuoteItemKindBI>
					<KindCode>TS</KindCode>
					<KindName>第三方特约险</KindName>
					<ItemKindNo>'.++$i.'</ItemKindNo>
					<SumInsured>0.0</SumInsured>
					<UnitInsured>0.0</UnitInsured>
					<Quantity>0</Quantity>
					<RelatedInd>0</RelatedInd>
					<KindInd>2</KindInd>
					</CarQuoteItemKindBI>';	
						break;	
				}
			}

				$business_XML="<CarQuoteItemKindBIList>{$items_xml}</CarQuoteItemKindBIList>";
				return $business_XML;


		}


}



