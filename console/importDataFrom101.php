#!/usr/bin/php -q
<?php
if(PHP_SAPI != 'cli')
{
	die('This script can only run in the command line mode！');
}
$app_dir = dirname(__FILE__)."/..";
$config = null;
$conffile = $app_dir.'/config/config.php';
if(!is_file($conffile))
{
	die("Not find config file '{$conffile}'！\n");
}

$config = require($conffile);

if(!is_array($config) || !array_key_exists('DBServers',$config) ||
   !array_key_exists('master',$config['DBServers']) ||
   !array_key_exists('DBHost'    ,$config['DBServers']['master']) ||
   !array_key_exists('DBPort'    ,$config['DBServers']['master']) ||
   !array_key_exists('DBUserName',$config['DBServers']['master']) ||
   !array_key_exists('DBPassword',$config['DBServers']['master']) ||
   !array_key_exists('Database'  ,$config['DBServers']['master'])
   )
{
	die("Configuration is not correct!\n please check the configuration file: '{$conffile}'！\n");
}
$DBHost     = $config['DBServers']['master']['DBHost'    ];
$DBPort     = $config['DBServers']['master']['DBPort'    ];
$DBUserName = $config['DBServers']['master']['DBUserName'];
$DBPassword = $config['DBServers']['master']['DBPassword'];
$desdbase   = $config['DBServers']['master']['Database'  ];

$conn = mysql_connect("{$DBHost}:{$DBPort}",$DBUserName,$DBPassword);
if(!$conn)
{
	die("Could not connect to {$DBHost}:{$DBPort}:".mysql_error().'\n');
}


if(!mysql_select_db($desdbase))
{
	die("Database:{$desdbase} does not exist!\n");
}

$srcdbase = '';
if(!empty($argv[1]))
{
	$srcdbase = $argv[1];
}
else
{
	fwrite(STDOUT,'Please input source database name:');
	$srcdbase = chop(fgets(STDIN));
}

if(!mysql_select_db($srcdbase))
{
	die("Database '{$srcdbase}' does not exist!\n");
}

$tables = array(
 'park'						   => 'park'						  ,
 'park_seq'					   => 'park_seq'					  ,
 'account_appointment'         => 'account_appointment'           ,
 'account_appointment_seq'     => 'account_appointment_seq'       ,
 'account_track'               => 'account_track'                 ,
 'account_track_seq'           => 'account_track_seq'             ,
 'accounts'                    => 'accounts'                      ,
 'accounts_seq'                => 'accounts_seq'                  ,
 'daily_analysis'              => 'daily_analysis'                ,
 'daily_analysis_seq'          => 'daily_analysis_seq'            ,
 'filters'                     => 'filters'                       ,
 'groups'                      => 'groups'                        ,
 'groups_seq'                  => 'groups_seq'                    ,
 'homes'                       => 'homes'                         ,
 'homes_seq'                   => 'homes_seq'                     ,
 'insurance_account_cli'       => 'insurance_account_cli'         ,
 'insurance_account_cli_seq'   => 'insurance_account_cli_seq'     ,
 'list_data'                   => 'list_data'                     ,
 'list_data_seq'               => 'list_data_seq'                 ,
 'list_match'                  => 'list_match'                    ,
 'list_match_seq'              => 'list_match_seq'                ,
 'notices'                     => 'notices'                       ,
 'notices_seq'                 => 'notices_seq'                   ,
 'permission'                  => 'permission'                    ,
 'permission_actions'          => 'permission_actions'            ,
 'permission_fields'           => 'permission_fields'             ,
 'permission_modules'          => 'permission_modules'            ,
 'permission_seq'              => 'permission_seq'                ,
 'result_report'               => 'result_report'                 ,
 'result_report_seq'           => 'result_report_seq'             ,
 'sms_mas_status'              => 'sms_mas_status'                ,
 'sms_notify'                  => 'sms_notify'                    ,
 'sms_notify_seq'              => 'sms_notify_seq'                ,
 'users'                       => 'users'                         ,
 'users_seq'                   => 'users_seq'                     ,
 'zswitch_cc_account_evaluate' => 'zswitch_cc_account_evaluate'   ,
 'zswitch_cc_agent_cdr'        => 'zswitch_cc_agent_cdr'          ,
 'zswitch_cc_queue_cdr'         => 'zswitch_cc_queue_cdr'         ,
 'zswitch_cc_vipnumber'         => 'zswitch_cc_vipnumber'         ,
//version:1.2.3版本表
'policy_draft_com'            => 'policy_draft_com'              ,
'policy_draft_com_seq'        => 'policy_draft_com_seq'          ,
'policy_draft'                => 'policy_draft'                  ,
'policy_draft_seq'            => 'policy_draft_seq'              ,
'policy_calculate_setting'    => 'policy_calculate_setting'      ,

);

function getTableCols($conn ,$table)
{
	$cols = array();
	$result = mysql_query("SHOW FULL COLUMNS FROM {$table};",$conn);
	while($row = mysql_fetch_assoc($result))
	{

		$type = preg_replace('/\(\.+\)/','',$row['Type']);
		$cols[$row['Field']] = $type;
	}
	return $cols;
}

$notchar = array('int','float','tinyint','smallint','mediumint','bigint','double','decimal');

foreach($tables as $st=>$dt)
{
	mysql_select_db($desdbase);
	mysql_query('set names utf8;');
	$result = mysql_query(" show tables like '{$dt}';");
	if(mysql_num_rows($result) == 0)
	{
		fwrite(STDOUT,"Table '{$dt}' is not found in the target database \n");
		continue;
	}

	$dcols = getTableCols($conn,$dt);
	fwrite(STDOUT,"Clear table '{$dt}'  in the target database \n");
	mysql_query("delete from {$dt};");

	mysql_select_db($srcdbase);
	mysql_query('set names  latin1;');

	$srcrs = mysql_query("select count(*) from {$st};");
	if(!$srcrs)
	{
		fwrite(STDOUT,"Table '{$st}' is not found in the source database \n");
		continue;
	}
	$row = mysql_fetch_row($srcrs);
	$rowcount = $row[0];
	fwrite(STDOUT,"Table '{$st}' have records:{$rowcount}  \n");

	$count = 0;
	$srcrow = false;

	while($rowcount>0)
	{
		if(!$srcrow)
		{
			mysql_select_db($srcdbase);
			mysql_query('set names  latin1;');
			$srcrs = mysql_query("select * from {$st} limit {$count},1000;");
            mysql_select_db($desdbase);
		    mysql_query('set names utf8;');
		}
		$srcrow = mysql_fetch_assoc($srcrs);
		if(empty($srcrow)) continue;


		$sql = "insert into {$dt}  ";
		$sqlcol = "";
		$sqlvalues = "";
		foreach($srcrow as $field=>$value)
		{
			if(array_key_exists($field,$dcols))
			{
				if(empty($sqlcol))
				{
					$sqlcol = $field;
				}
				else
				{
					$sqlcol .= ','.$field;
				}
				$val = '';
				if(in_array($dcols[$field],$notchar))
				{
					$val = $value;
					if(empty($val)) $val='null';
				}
				else
				{
					//对',",\,NULL进行转义
					$value = get_magic_quotes_gpc() ? $value : addslashes ($value);
					$val = "'{$value}'";
				}
				if(empty($sqlvalues))
				{
					$sqlvalues = $val;
				}
				else
				{
					$sqlvalues .= ','.$val;
				}
			}
		}
		$sql .= "({$sqlcol}) values({$sqlvalues});";
	    //mysql_select_db($desdbase);
		//mysql_query('set names utf8;');
		mysql_query($sql);
		$count++;
		$rowcount--;
		fwrite(STDOUT,"\rImport table {$dt}:{$count}");
	}
	fwrite(STDOUT, "\n");
}


$sql = "select id ,name from  company ;";

mysql_select_db($srcdbase);
mysql_query('set names  latin1;');
$comp = mysql_query($sql);

if(!$comp)
{
	die("Import data complete!\n");
}

mysql_select_db($desdbase);
mysql_query('set names utf8;');
fwrite(STDOUT,"update table accounts,please wait....\n");
while($row = mysql_fetch_assoc($comp))
{
	$sql = "insert into dropdown(module_name,field,save_value,show_value) ";
	$sql .= "values('accounts','company','{$row['name']}','{$row['name']}')";
	mysql_query($sql);

	$sql = "insert into dropdown(module_name,field,save_value,show_value) ";
	$sql .= "values('Recycle','company','{$row['name']}','{$row['name']}')";
	mysql_query($sql);

	$sql = "insert into dropdown(module_name,field,save_value,show_value) ";
	$sql .= "values('Telemarketing','company','{$row['name']}','{$row['name']}')";
	mysql_query($sql);

	$sql = "insert into dropdown(module_name,field,save_value,show_value) ";
	$sql .= "values('HandOut','company','{$row['name']}','{$row['name']}')";
	mysql_query($sql);

	$sql = "update accounts set company='{$row['name']}' where company='{$row['id']}';";
}

fwrite(STDOUT,"Import data complete!\n");





?>