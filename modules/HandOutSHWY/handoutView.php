<?
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$mod_strings;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
}
if(isset($_REQUEST['recordid']) && !empty($_REQUEST['recordid'])){
    $smarty->assign("SIT",$mod->getGroupUser($_REQUEST['recordid']));
/*if(!$CURRENT_IS_ADMIN){
    $smarty->assign("SIT",$mod->getGroupUser());*/
    $smarty->display("{$module}/sits.tpl");
}else{
    $smarty->assign("GROUP",$mod->getWorkGroup());
    $smarty->assign("SIT",$mod->getGroupUser());
    $smarty->assign("MOD",$mod_strings);
    $smarty->display("{$module}/handoutView.tpl");
}