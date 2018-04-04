<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
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
    if(isset($_GET['recordid']))
    {
        $recordid = $_GET['recordid'];
    }
    else
    {
        return_ajax('error','没有记录ID!');
        die();
    }
}
$result = $mod->recycleOneRecordset($recordid,
                         $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
                         $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
                         );
if($result === 0)
    return_ajax('rediect',"index.php?module={$module}&action=index");
else
    return_ajax('error',$result);
?>