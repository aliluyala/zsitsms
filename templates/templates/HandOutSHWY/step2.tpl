{include file="TitleBar.tpl"}
<div id="handout_num">
    <label id="handout_wait">{$OPERATION_HANDOUT_WAIT_FIELD.value}</label>
    <label for="{$OPERATION_HANDOUT_WAIT_FIELD.name}" title="{$OPERATION_HANDOUT_WAIT_FIELD.title}">{$OPERATION_HANDOUT_WAIT_FIELD.label}</label>
</div>
<div id="handout_option">
<form id="form_edit_view">
    <input type="hidden" id="editview_module_name" value="{$MODULE}"/>
    <input type="hidden" name="operation" value="{$OPERATION}"/>
    <input type="hidden" name="user_attach" value="{$REQUEST.user_attach}">
    <!-- <input type="hidden" name="group_attach" value="{$REQUEST.group_attach}"> -->
    <input type="hidden" name="user_create" value="{$REQUEST.user_create}">
    <input type="hidden" name="date_create" value="{$REQUEST.date_create}">
    <input type="hidden" name="expiration_date" value="{$REQUEST.expiration_date}">
    <input type="hidden" name="expiration_date_end" value="{$REQUEST.expiration_date_end}">
    <input type="hidden" name="register_date" value="{$REQUEST.register_date}">
    <input type="hidden" name="register_date_end" value="{$REQUEST.register_date_end}">
    <input type="hidden" name="register_month" value="{$REQUEST.register_month}">
    <input type="hidden" name="batch" value="{$REQUEST.batch}">
    <input type="hidden" name="type" value="{$REQUEST.type}">
    <input type="hidden" name="area" value="{$REQUEST.area}">
    <input type="hidden" name="park" value="{$REQUEST.park}">
    <input type="hidden" name="team" value="{$REQUEST.team}">
    <input type="hidden" name="company" value="{$REQUEST.company}">
    <input type="hidden" name="model" value="{$REQUEST.model}">
    <input type="hidden" name="status" value="{$REQUEST.status}">
    <input type="hidden" name="report" value="{$REQUEST.report}">
    <div  style="font-size:12px">
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
                    {include file="UI/21.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_WAY_FIELD}
                </td>
            </tr>
            <tr>
                <td class="client_detailview_label_2">
                    <label for="{$OPERATION_HANDOUT_NUM_FIELD.name}" title="{$OPERATION_HANDOUT_NUM_FIELD.title}">{$OPERATION_HANDOUT_NUM_FIELD.label}</label>
                </td>
                <td class="client_detailview_value_2">
                    {include file="UI/6.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_NUM_FIELD}
                </td>
                <td class="client_detailview_label_2">
                    <label for="{$OPERATION_HANDOUT_SIT_FIELD.name}" title="{$OPERATION_HANDOUT_SIT_FIELD.title}">{$OPERATION_HANDOUT_SIT_FIELD.label}</label>
                </td>
                <td class="client_detailview_value_2">
                    <label>
                        <input type="text" id="{$OPERATION_HANDOUT_SIT_FIELD.name}" value="{$OPERATION_HANDOUT_SIT_FIELD.value}" ui = "{$OPERATION_HANDOUT_SIT_FIELD.UI}" mandatory = "{$OPERATION_HANDOUT_SIT_FIELD.mandatory}" label = "{$OPERATION_HANDOUT_SIT_FIELD.label}" readonly="readonly"  style="width:98%"/>
                        {include file="UI/101.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_SIT_FIELD}
                    </label>
                    <label style="display:none;">{include file="UI/50.UI.tpl" FIELDINFO=$OPERATION_HANDOUT_GROUP_FIELD}</label>
                </td>
            </tr>
        </table>
    </div>
</form>
</div>
<div class="CLIENT_JOBVIEW_BUTTONS">
    <button class="module_seting_button" operation="handout">分发</button>
    <button class="module_seting_button" operation="recycle">回收</button>
    <button class="module_seting_button" operation="delete">删除</button>
    <button class="module_seting_button" operation="prev">上一步</button>
</div>
<script type="text/javascript">
    zswitch_ui_form_init("#form_edit_view");
    var num = {$OPERATION_HANDOUT_WAIT_FIELD.value};
    var operation_handout = "{$HANDOUT_PREMISS}";
    var operation_recycle = "{$RECYCLE_PREMISS}";
    var operation_delete  = "{$DELETE_PREMISS}";

    {literal}
    $(".module_seting_button").button().click(function(){
        var oper = $(this).attr("operation");
        var mod = $("#editview_module_name").val();
        $("input[name='operation']").val(oper);
        if(oper == 'prev'){
            zswitch_load_client_view("index.php?module="+mod+"&action=index","form_edit_view");
        }else if(oper == 'delete'){
            handout.del(mod,num);
        }else if(oper == 'handout'){
            handout.handoutStep(mod);
        }else if(oper == 'recycle'){
            handout.recycle(mod);
        }
    });
    if(!operation_handout){
        $("button[operation='handout']").attr('disabled', 'disabled');
        $("button[operation='handout']").addClass('ui-state-disabled');
    }
    if(!operation_recycle){
        $("button[operation='recycle']").attr('disabled', 'disabled');
        $("button[operation='recycle']").addClass('ui-state-disabled');
    }
    if(!operation_delete){
        $("button[operation='delete']").attr('disabled', 'disabled');
        $("button[operation='delete']").addClass('ui-state-disabled');
    }

    $("#handout_num label[for=handout_wait],#handout_num #handout_wait").click(function(){
        handout.addSourcePopup();
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
            handout.set();
            handout.moveToObj("#handout_sit");
            handout.show();
        }
    });
    /*$("#chceUsr").bind("click",function(){
        var evt = event || window.event;
        if(evt.stopPropagation) {
            evt.stopPropagation();
        }
        evt.cancelBubble = true;
    });
    $(document).bind("click",function(){
        var evt = event || window.event;
        var srcElement = evt.srcElement || evt.target;
        if(srcElement.id != 'handout_sit'){
            handout.hide();
        }
    });*/
    {/literal}
</script>