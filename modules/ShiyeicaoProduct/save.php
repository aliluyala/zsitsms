<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
    return_ajax('error','你的权限不能进行该项操作！');
    die();
}


$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
}
global $recordid;
if(!isset($recordid))
{
    if(isset($_POST['recordid']))
        $recordid = $_POST['recordid'];
    else
    {
        return_ajax('error','没有记录ID!');
        die();
    }
}
global $operation;
if(!isset($operation))
{
    if(isset($_POST['operation']))
        $operation = $_POST['operation'];
    else
    {
        return_ajax('error','operation错误!');
        die();
    }
}

global $save_datas;
if(!isset($save_datas))
{
    if($CURRENT_IS_ADMIN)
    {
        $save_datas = Array();
        foreach($mod->editFields as $field)
        {
            if(isset($_POST[$field])) $save_datas[$field] = $_POST[$field];
        }
    }
    else
    {
        $save_datas = validationFieldsModifyPermission($module,$_POST);
    }
}

$result = 0;

if($operation == 'edit')
{
    $result = $mod->updateOneRecordset($recordid,
                             $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
                             $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module),
                             $save_datas);
}

elseif($operation == 'create' )
{
    $result = $mod->insertOneRecordset($recordid,$save_datas);
}
elseif($operation == 'copy')
{
    if(!isset($_POST['new_recordid']))
    {
        return_ajax('error','new_recordid错误!');
        die();
    }
    $recordid = $_POST['new_recordid'];
    $result = $mod->insertOneRecordset($recordid,$save_datas);
}
global $return_module;
if(!isset($return_module))
{
    if(!isset($_GET['return_module'])) $return_module = $module;
    else $return_module = $_GET['return_module'];
}
global $return_action ;
if(!isset($return_action))
{
    if(!isset($_GET['return_action'])) $return_action = 'detailView';
    else $return_action = $_GET['return_action'];
}
global $return_recordid;
if(!isset($return_recordid))
{
    if(!isset($_GET['return_recordid'])) $return_recordid = $recordid;
    else $return_recordid = $_GET['return_recordid'];
}
if($result === 1)
{
    return_ajax('rediect',"index.php?module={$return_module}&action={$return_action}&recordid={$return_recordid}");
}
else
{
    return_ajax('error',$result);
}
?>