{if $ACCOUNTID eq -1 || $ACCOUNTID eq 0}
<div class="ui-widget">
    <div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
        <table>
            <tr>
                <td><span class="ui-icon ui-icon-info" ></span></td>
                <td><strong>无效名单！</strong></td>
                <td>请检查该名单是否为有效名单。</td>
            </tr>
        </table>
    </div>
</div>
{else}
<form id="detailview_account_track_add_dlg_form">
    <input type="hidden" name="recordid" value="{$RECORDID}"/>
    <input type="hidden" name = "accountid" value="{$ACCOUNTID}"/>
    <input type="hidden" name="operation" value="create"/>
    <table class="client_detailview_table" cellspacing="0">
    <tr>
        <td class="client_detailview_label_1">
            <label for="{$INTENTION_FIELD.name}" title="{$INTENTION_FIELD.title}">意向程度</label>
        </td>
        <td class="client_detailview_value_1">{include file="UI/20.UI.tpl" FIELDINFO=$INTENTION_FIELD}</td>
    </tr>
    <tr>
        <td class="client_detailview_label_1">
            <label for="{$STATUS_FIELD.name}" title="{$STATUS_FIELD.title}">销售结果</label>
        </td>
        <td class="client_detailview_value_1">{include file="UI/20.UI.tpl" FIELDINFO=$STATUS_FIELD}</td>
    </tr>
    <tr>
        <td class="client_detailview_label_1">
            <label for="{$DESC_FIELD.name}" title="{$DESC_FIELD.title}">销售说明</label>
        </td>
        <td class="client_detailview_value_1">{include file="UI/26.UI.tpl" FIELDINFO=$DESC_FIELD}</td>
    </tr>
    <tr>
        <td class="client_detailview_label_1">
            <label for="{$PRESET_TIME_FIELD.name}" title="{$PRESET_TIME_FIELD.title}">预约时间</label>
        </td>
        <td class="client_detailview_value_1">{include file="UI/31.UI.tpl" FIELDINFO=$PRESET_TIME_FIELD}</td>
    </tr>
    <tr>
        <td class="client_detailview_label_1">
            <label for="{$REMARK_FIELD.name}" title="{$REMARK_FIELD.title}">备注</label>
        </td>
        <td class="client_detailview_value_1">{include file="UI/9.UI.tpl" FIELDINFO=$REMARK_FIELD}</td>
    </tr>

    </table>
</form>
{/if}


<script>
{literal}
	zswitch_ui_form_init("#detailview_account_track_add_dlg_form");
{/literal}
</script>