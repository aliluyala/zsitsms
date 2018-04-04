{include file="TitleBar.tpl"}
<script type="text/javascript" src="{$SCRIPTS}/zswitch-accountsSHWY.js"></script>
<div id="handout_num">
    <label id="handout_wait">{$OPERATION_HANDOUT_WAIT_FIELD.value}</label>
    <label for="{$OPERATION_HANDOUT_WAIT_FIELD.name}" title="{$OPERATION_HANDOUT_WAIT_FIELD.title}">{$OPERATION_HANDOUT_WAIT_FIELD.label}</label>
</div>
<div id="handout_option">
    <form id="form_edit_view">
        <input type="hidden" id="editview_module_name" value="{$MODULE}"/>
        <input type="hidden" name="operation" value="{$OPERATION}"/>
        <div  style="font-size:12px">
            <table class="client_detailview_table" cellspacing="0">
                <tr>
                    <!-- <td class="client_detailview_label_3">
                        <label for="{$OPERATION_GROUP_FIELD.name}" title="{$OPERATION_GROUP_FIELD.title}">{$OPERATION_GROUP_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/51.UI.tpl" FIELDINFO=$OPERATION_GROUP_FIELD}
                    </td> -->
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_USER_FIELD.name}" title="{$OPERATION_USER_FIELD.title}">{$OPERATION_USER_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/51.UI.tpl" FIELDINFO=$OPERATION_USER_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_USER_CREATE_FIELD.name}" title="{$OPERATION_USER_CREATE_FIELD.title}">{$OPERATION_USER_CREATE_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/51.UI.tpl" FIELDINFO=$OPERATION_USER_CREATE_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_TEAM_FIELD.name}" title="{$OPERATION_TEAM_FIELD.title}">{$OPERATION_TEAM_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/5.UI.tpl" FIELDINFO=$OPERATION_TEAM_FIELD}
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_AREA_FIELD.name}" title="{$OPERATION_AREA_FIELD.title}">{$OPERATION_AREA_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/20.UI.tpl" FIELDINFO=$OPERATION_AREA_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_PARK_FIELD.name}" title="{$OPERATION_PARK_FIELD.title}">{$OPERATION_PARK_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/26.UI.tpl" FIELDINFO=$OPERATION_PARK_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_MODEL_FIELD.name}" title="{$OPERATION_MODEL_FIELD.title}">{$OPERATION_MODEL_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/5.UI.tpl" FIELDINFO=$OPERATION_MODEL_FIELD}
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_COMPANY_FIELD.name}" title="{$OPERATION_COMPANY_FIELD.title}">{$OPERATION_COMPANY_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/27.UI.tpl" FIELDINFO=$OPERATION_COMPANY_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_DATE_CREATE_FIELD.name}" title="{$OPERATION_DATE_CREATE_FIELD.title}">{$OPERATION_DATE_CREATE_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/30.UI.tpl" FIELDINFO=$OPERATION_DATE_CREATE_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_BATCH_FIELD.name}" title="{$OPERATION_BATCH_FIELD.title}">{$OPERATION_BATCH_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/20.UI.tpl" FIELDINFO=$OPERATION_BATCH_FIELD}
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_EXPIRATION_DATE_FIELD.name}" title="{$OPERATION_EXPIRATION_DATE_FIELD.title}">{$OPERATION_EXPIRATION_DATE_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/31.UI.tpl" FIELDINFO=$OPERATION_EXPIRATION_DATE_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_EXPIRATION_DATE_END_FIELD.name}" title="{$OPERATION_EXPIRATION_DATE_END_FIELD.title}">{$OPERATION_EXPIRATION_DATE_END_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/31.UI.tpl" FIELDINFO=$OPERATION_EXPIRATION_DATE_END_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_TYPE_FIELD.name}" title="{$OPERATION_TYPE_FIELD.title}">{$OPERATION_TYPE_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/20.UI.tpl" FIELDINFO=$OPERATION_TYPE_FIELD}
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_REGISTER_DATE_FIELD.name}" title="{$OPERATION_REGISTER_DATE_FIELD.title}">{$OPERATION_REGISTER_DATE_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/30.UI.tpl" FIELDINFO=$OPERATION_REGISTER_DATE_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_REGISTER_DATE_END_FIELD.name}" title="{$OPERATION_REGISTER_DATE_END_FIELD.title}">{$OPERATION_REGISTER_DATE_END_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/30.UI.tpl" FIELDINFO=$OPERATION_REGISTER_DATE_END_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_REGISTER_MONTH_FIELD.name}" title="{$OPERATION_REGISTER_MONTH_FIELD.title}">{$OPERATION_REGISTER_MONTH_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/20.UI.tpl" FIELDINFO=$OPERATION_REGISTER_MONTH_FIELD}
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_STATUS_FIELD.name}" title="{$OPERATION_STATUS_FIELD.title}">{$OPERATION_STATUS_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/20.UI.tpl" FIELDINFO=$OPERATION_STATUS_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                        <label for="{$OPERATION_REPORT_FIELD.name}" title="{$OPERATION_REPORT_FIELD.title}">{$OPERATION_REPORT_FIELD.label}</label>
                    </td>
                    <td class="client_detailview_value_3">
                        {include file="UI/{$OPERATION_REPORT_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_REPORT_FIELD}
                    </td>
                    <td class="client_detailview_label_3">
                    </td>
                    <td class="client_detailview_value_3">
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <div class="CLIENT_JOBVIEW_BUTTONS">
        <button class="module_seting_button" operation="next">下一步</button>
    </div>
</div>
<script type="text/javascript">
    zswitch_ui_form_init("#form_edit_view");

    {literal}
    $(".module_seting_button").button().click(function(){
        var oper = $(this).attr("operation");
        var mod = $("#editview_module_name").val();
        if(oper == 'next'){
            /*$.post("index.php?module="+mod+"&action=step2",$('#form_edit_view').serialize(),function(data){
                $("#handout_option").html(data);
            });*/
            zswitch_load_client_view("index.php?module="+mod+"&action=step2","form_edit_view");
        }
    });
    $("#handout_num label[for=handout_wait],#handout_num #handout_wait").click(function(){
        handout.addSourcePopup();
    });
    $("form input,form select").change(function(){
        handout.getCount($("#editview_module_name").val());
    });
    function zswitch_ui_init_50(obj)
    {
        if(obj.attr("ui_init") == "true") return ;

        var showinput = obj.next();
        var selectbut = showinput.next();
        var deletebut = selectbut.next();
        var selectdlg = deletebut.next();
        var dt = new Date();
        var dlgid = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());

        selectdlg.attr("id",dlgid);
        obj.attr("ui_selecter_id",dlgid+"_value");
        showinput.attr("ui_selecter_id",dlgid+"_show_value");
        selectbut.attr("ui_selecter_id",dlgid);
        $("#"+dlgid).dialog({
              autoOpen: false,
              height: 450,
              width: 700,
              modal: true,
              appendTo: "#main_view_client",
              dialogClass:"dialog_default_class",
              buttons:{
                    '确定':function(){
                            if($(this).find("[name=associate_select_radio]").is(":checked"))
                            {
                                var sel = $(this).find("[name=associate_select_radio]:checked");
                                $("[ui_selecter_id="+$(this).attr("id")+"_value]").val(sel.val());
                                $("[ui_selecter_id="+$(this).attr("id")+"_show_value]").val(sel.attr("show_value"));
                                handout.getCount($("#editview_module_name").val());
                            }
                            $(this).dialog("close");
                         },
                    '取消':function(){
                            $(this).dialog("close");
                         }

                    },

               open: function( event, ui ) {
                    load_associate_selecter($(this).attr("module"),$(this).attr("show_field"),"","",0,$(this).attr("id"),$(this).attr("list_filter_field"),$(this).attr("list_filter_value"),$(this).attr("list_fields"));
               }

        });

        deletebut.click(function(){
            $(this).prev().prev().val("");
            $(this).prev().prev().prev().val("");
            handout.getCount($("#editview_module_name").val());
        });
        selectbut.click(function(){
            var dlg = $("#"+$(this).attr("ui_selecter_id"));
            dlg.dialog("open");
        });
        obj.attr("ui_init","true");
    }
    {/literal}
</script>