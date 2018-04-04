<table id="chceUsr" class="calDayHour" cellpadding="5" cellspacing="0">
    <tr>
        <td><b>{$MOD.group}</b></td>
        <td><b>{$MOD.optional_user}</b></td>
        <td></td>
        <td><b>{$MOD.selected_user}</b></td>
    </tr>
    <tr>
        <td>
            <select id="available_groups" size="10" name="available_groups" class="txtBox" style="width: 100%">
                {foreach from=$GROUP item=group}
                    <option value="{$group.id}">{$group.name}</option>
                {/foreach}
            </select>
        </td>
        <td id="available_fields_td">
            {include file="HandOut/sits.tpl" FIELDINFO=$SIT}
        </td>
        <td width="6%">
            <div align="center">
                <input type="button" name="Button" class="handout_choose" value="&nbsp;››&nbsp;" onclick="copySelectedOptions('available_fields', 'selected_merge_fields')" class="crmButton small"><br><br>
                <input type="button" name="Button1" class="handout_choose" value="&nbsp;‹‹&nbsp;" onclick="removeSelectedOptions('selected_merge_fields')" class="crmButton small"><br><br>
            </div>
        </td>
        <td>
            <select id="selected_merge_fields" multiple="" size="10" name="selected_merge_fields" class="txtBox" style="width: 100%;min-width: 90px;"></select>
        </td>
    </tr>
</table>
<script language="javascript">
    $(".handout_choose").button();
    if($("#handout_sit").val() != "")
        setSelectedOptions();
    $("#available_groups").bind("click","option",function(){
        var text = $(this).text();
        var value = $(this).val();
        var url = "index.php?module=HandOut&action=handoutView";
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'text',
            data: "recordid="+value,
            success : function(response){
                $("#available_fields_td").html(response);
            }
        });
    });
</script>