<?php
if(isset($_GET['listfields']) && $_GET['listfields'] == 'insurance_create_page'){
    $return_module = "Accounts";
    $return_action = "detailView";
    $return_recordid = $_GET["fieldvalue"];
    require_once("createView.php");
}else{
    $operation_allow = true;

    $url = " var url = 'index.php?module=Insurance&action=associateEditView&associateField=autoid&fieldvalue='";
    $url .= "+$('input[name=\'associate_value\']').val()+'&listfields=calculate_no&recordid='+$(this).attr('recordid')";
    $url .= "+'&return_module=Accounts&return_action=detailView&return_recordid='+$('input[name=\'associate_value\']').val();";
    $url .= "$('#detailview_info_tabs ul li:eq(3) a').attr('href',url);";
    $url .= "$('#detailview_info_tabs ul li:eq(3) a').click();";

    $operations = array(
        array(
            'title' => '重新算价',
            'name' => '重算',
            'url' => $url,
        ),
    );
    require_once(_ROOT_DIR.'/common/associateListView.php');
}

?>