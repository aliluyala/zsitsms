{include file="TitleBar.tpl"}
<script type="text/javascript" src="{$SCRIPTS}/zswitch-accounts.js"></script>
<div id="handout_num">
    <label id="handout_wait">{$OPERATION_HANDOUT_WAIT_FIELD.value}</label>
    <label for="{$OPERATION_HANDOUT_WAIT_FIELD.name}" title="{$OPERATION_HANDOUT_WAIT_FIELD.title}">{$OPERATION_HANDOUT_WAIT_FIELD.label}</label>
</div>
<div id="handout_option">
    <form id="form_edit_view">
        <input type="hidden" id="editview_module_name" value="{$MODULE}"/>
        <input type="hidden" name="operation" value="{$OPERATION}"/>
        <div  style="font-size:12px;padding:1px;">
            <div id="editview_blocks_accordion_{$LABEL_TITLE_FILTER.name}"  style="text-align:left;">
                <h3>{$LABEL_TITLE_FILTER.label}</h3>
                <div style="padding:1px;">
                    <table class="client_detailview_table" cellspacing="0">
                        <tr>
                            <!-- <td class="client_detailview_label_3">
                                <label for="{$OPERATION_GROUP_FIELD.name}" title="{$OPERATION_GROUP_FIELD.title}">{$OPERATION_GROUP_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_GROUP_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_GROUP_FIELD}
                            </td> -->
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_USER_FIELD.name}" title="{$OPERATION_USER_FIELD.title}">{$OPERATION_USER_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_USER_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_USER_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_USER_CREATE_FIELD.name}" title="{$OPERATION_USER_CREATE_FIELD.title}">{$OPERATION_USER_CREATE_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_USER_CREATE_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_USER_CREATE_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_AREA_FIELD.name}" title="{$OPERATION_AREA_FIELD.title}">{$OPERATION_AREA_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_AREA_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_AREA_FIELD}
                            </td>
                        </tr>
                        <tr>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_DATE_CREATE_FIELD.name}" title="{$OPERATION_DATE_CREATE_FIELD.title}">{$OPERATION_DATE_CREATE_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_DATE_CREATE_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_DATE_CREATE_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_DATE_CREATE_END_FIELD.name}" title="{$OPERATION_DATE_CREATE_END_FIELD.title}">{$OPERATION_DATE_CREATE_END_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_DATE_CREATE_END_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_DATE_CREATE_END_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_PARK_FIELD.name}" title="{$OPERATION_PARK_FIELD.title}">{$OPERATION_PARK_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_PARK_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_PARK_FIELD}
                            </td>
                        </tr>
                        <tr>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_REGISTER_DATE_FIELD.name}" title="{$OPERATION_REGISTER_DATE_FIELD.title}">{$OPERATION_REGISTER_DATE_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_REGISTER_DATE_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_REGISTER_DATE_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_REGISTER_DATE_END_FIELD.name}" title="{$OPERATION_REGISTER_DATE_END_FIELD.title}">{$OPERATION_REGISTER_DATE_END_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_REGISTER_DATE_END_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_REGISTER_DATE_END_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_REGISTER_MONTH_FIELD.name}" title="{$OPERATION_REGISTER_MONTH_FIELD.title}">{$OPERATION_REGISTER_MONTH_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_REGISTER_MONTH_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_REGISTER_MONTH_FIELD}
                            </td>
                        </tr>
                        <tr>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_BATCH_FIELD.name}" title="{$OPERATION_BATCH_FIELD.title}">{$OPERATION_BATCH_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_BATCH_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_BATCH_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_TYPE_FIELD.name}" title="{$OPERATION_TYPE_FIELD.title}">{$OPERATION_TYPE_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_TYPE_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_TYPE_FIELD}
                            </td>
                            <td class="client_detailview_label_3"></td>
                            <td class="client_detailview_value_3"></td>
                        </tr>
                        <tr>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_STATUS_FIELD.name}" title="{$OPERATION_STATUS_FIELD.title}">{$OPERATION_STATUS_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_STATUS_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_STATUS_FIELD}
                            </td>
                            <td class="client_detailview_label_3">
                                <label for="{$OPERATION_REPORT_FIELD.name}" title="{$OPERATION_REPORT_FIELD.title}">{$OPERATION_REPORT_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_3">
                                {include file="UI/{$OPERATION_REPORT_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_REPORT_FIELD}
                            </td>
                            <td class="client_detailview_label_3"></td>
                            <td class="client_detailview_value_3"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <script type="text/javascript">
                $("#editview_blocks_accordion_{$LABEL_TITLE_FILTER.name}").accordion({ldelim}
                    active:0,
                    collapsible: true,
                    heightStyle:"content" {rdelim} );
            </script>
            <div id="editview_blocks_accordion_{$LABEL_TITLE_HANDOUT.name}"  style="text-align:left;">
                <h3>{$LABEL_TITLE_HANDOUT.label}</h3>
                <div style="padding:1px;">
                    <table class="client_detailview_table" cellspacing="0">
                        <tr>
                            <td class="client_detailview_label_2">
                                <label for="{$OPERATION_HANDOUT_WAIT_FIELD.name}" title="{$OPERATION_HANDOUT_WAIT_FIELD.title}">{$OPERATION_HANDOUT_WAIT_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_2">
                                <span>{include file="UI/{$OPERATION_HANDOUT_WAIT_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_WAIT_FIELD}</span>
                            </td>
                            <td class="client_detailview_label_2">
                                <label for="{$OPERATION_HANDOUT_WAY_FIELD.name}" title="{$OPERATION_HANDOUT_WAY_FIELD.title}">{$OPERATION_HANDOUT_WAY_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_2">
                                {include file="UI/{$OPERATION_HANDOUT_WAY_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_WAY_FIELD}
                            </td>
                        </tr>
                        <tr>
                            <td class="client_detailview_label_2">
                                <label for="{$OPERATION_HANDOUT_NUM_FIELD.name}" title="{$OPERATION_HANDOUT_NUM_FIELD.title}">{$OPERATION_HANDOUT_NUM_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_2">
                                {include file="UI/{$OPERATION_HANDOUT_NUM_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_NUM_FIELD}
                            </td>
                            <td class="client_detailview_label_2">
                                <label for="{$OPERATION_HANDOUT_SIT_FIELD.name}" title="{$OPERATION_HANDOUT_SIT_FIELD.title}">{$OPERATION_HANDOUT_SIT_FIELD.label}</label>
                            </td>
                            <td class="client_detailview_value_2">
                                <label>
                                    <input type="text" id="{$OPERATION_HANDOUT_SIT_FIELD.name}" value="{$OPERATION_HANDOUT_SIT_FIELD.value}" ui = "{$OPERATION_HANDOUT_SIT_FIELD.UI}" mandatory = "{$OPERATION_HANDOUT_SIT_FIELD.mandatory}" label = "{$OPERATION_HANDOUT_SIT_FIELD.label}" readonly="readonly"  style="width:98%"/>
                                    {include file="UI/{$OPERATION_HANDOUT_SIT_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_SIT_FIELD}
                                </label>
                                <label style="display:none;">{include file="UI/{$OPERATION_HANDOUT_GROUP_FIELD.UI}.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_GROUP_FIELD}</label>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <script type="text/javascript">
                $("#editview_blocks_accordion_{$LABEL_TITLE_HANDOUT.name}").accordion({ldelim}
                    active:0,
                    collapsible: true,
                    heightStyle:"content" {rdelim} );
            </script>
        </div>
    </form>
    <div class="CLIENT_JOBVIEW_BUTTONS">
    <button class="module_seting_button" operation="handout">分发</button>
    <button class="module_seting_button" operation="recycle">回收</button>
    <button class="module_seting_button" operation="transfer">转移</button>
    <button class="module_seting_button" operation="delete">删除</button>
</div>
</div>
<script type="text/javascript">
    zswitch_ui_form_init("#form_edit_view");
    var operation_handout  = {$HANDOUT_PREMISS};
    var operation_recycle  = {$RECYCLE_PREMISS};
    var operation_transfer = {$TRANSFER_PREMISS};
    var operation_delete   = {$DELETE_PREMISS};

    {literal}
    var mod = $("#editview_module_name").val();
    if(!operation_handout){
        $("button[operation='handout']").attr('disabled', 'disabled');
        $("button[operation='handout']").addClass('ui-state-disabled');
    }
    if(!operation_recycle){
        $("button[operation='recycle']").attr('disabled', 'disabled');
        $("button[operation='recycle']").addClass('ui-state-disabled');
    }
    if(!operation_transfer){
        $("button[operation='transfer']").attr('disabled', 'disabled');
        $("button[operation='transfer']").addClass('ui-state-disabled');
    }
    if(!operation_delete){
        $("button[operation='delete']").attr('disabled', 'disabled');
        $("button[operation='delete']").addClass('ui-state-disabled');
    }

    $("#handout_num label[for=handout_wait],#handout_num #handout_wait").click(function(){
        handout.addSourcePopup(mod);
    });
    $("#form_edit_view table:eq(0) input,select").change(function(){
        handout.getCount(mod);
    });

    $(".module_seting_button").button().click(function(){
        var oper = $(this).attr("operation");
        $("input[name='operation']").val(oper);
        if(oper == 'delete'){
            handout.del(mod,$("#handout_num #handout_wait").text());
        }else if(oper == 'handout'){
            handout.handoutStep(mod);
        }else if(oper == 'recycle'){
            handout.recycle(mod);
        }else if(oper == 'transfer'){
            handout.transfer(mod);
        }
    });

    $("input[name='handout_way']").change(function() {
        if($(this).val() == 'HANDOUT_BY_SIT'){
            $("#handout_sit").parent("label").show();
            $("input[name='handout_group']").parent("label").hide();
        }else{
            $("#handout_sit").parent("label").hide();
            $("input[name='handout_group']").parent("label").show();
        }
    });
    $("#handout_sit").click(function(){
        if($("#chceUsr").length != 0){
            handout.hide();
        }else{
            handout.set(mod);
            handout.moveToObj("#handout_sit");
            handout.show();
        }
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
                                $("[ui_selecter_id="+$(this).attr("id")+"_value]").change();
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
            $("[ui_selecter_id="+dlgid+"_value]").change();
        });
        selectbut.click(function(){
            var dlg = $("#"+$(this).attr("ui_selecter_id"));
            dlg.dialog("open");
        });
        obj.attr("ui_init","true");
    }
    {/literal}
</script>