{if $CURLSTR.data neq ""}
<form id="detailview_account_track_add_dlg_form">
    <input type="hidden" name="recordid" value="{$RECORDID}"/>
    <input type="hidden" name = "accountid" value="{$ACCOUNTID}"/>
    <input type="hidden" name="operation" value="create"/>
<table class="client_detailview_table" cellspacing="0" style="text-align:center">
    <tr >
        <td class="client_detailview_label_1">
            <label for="" title=" " >被保险人:</label>
        </td>
        <td class="client_detailview_value_1"><input type="text" name="policy_holder" value="{$CURLSTR['data']['insurant']}" ui="5" mandatory="1" label="被保险人" id="vin" class="responsive_width_98" readonly></td>
        <td class="client_detailview_label_1">
        <label for=" " title=" " >保险公司</label>
        </td>
        <td class="client_detailview_value_1"><input type="text" name="vin" value="{$CURLSTR['data']['insurance_company']}" ui="5" mandatory="1" label="保险公司" id="vin" class="responsive_width_98" readonly></td>

    </tr>
    <tr>

        <td class="client_detailview_label_1">
            <label for=" " title=" " >应交保费</label>
            </td>
            <td class="client_detailview_value_1"><input type="text" name="vin" value="{$CURLSTR['data']['premium']}" ui="5" mandatory="1" label="应交保费" id="vin" class="responsive_width_98" readonly>
        </td>
         <td class="client_detailview_label_1" >
            <label for=" " title=" " >上险次数</label>
            </td>
            <td class="client_detailview_value_1" ><input type="text" name="vin" value="{$CURLSTR['data']['claims']}" ui="5" mandatory="1" label="上险次数" id="vin" class="responsive_width_98" readonly >
        </td>

    </tr>
    <tr>
    <td class="client_detailview_label_1">
            <label for=" " title=" " >折扣</label>
            </td>
            <td class="client_detailview_value_1"><input type="text" name="vin" value="{$CURLSTR['data']['discount']}" ui="5" mandatory="1" label="折扣" id="vin" class="responsive_width_98" readonly>
        </td>
        <td class="client_detailview_label_1">
            <label for=" " title=" " >终保日期</label>
            </td>
            <td class="client_detailview_value_1"><input type="text" name="vin" value="{$CURLSTR['data']['end_date']}" ui="5" mandatory="1" label="终保日期" id="vin" class="responsive_width_98" readonly>
        </td>

    </tr>
	<tr>
		<td class="client_detailview_label_1">
            <label for=" " title=" " >型号代码</label>
            </td>
            <td class="client_detailview_value_1"><input type="text" name="model_code" value="{$CURLSTR['data']['model_code']}" ui="5" mandatory="1" label="型号代码" id="vin" class="responsive_width_98" readonly>
        </td>
        <td class="client_detailview_label_1">
            <label for=" " title=" " >购置价</label>
            </td>
            <td class="client_detailview_value_1"><input type="text" name="buying_price" value="{$CURLSTR['data']['buying_price']}" ui="5" mandatory="1" label="购置价" id="vin" class="responsive_width_98" readonly>
        </td>
    </tr>
    <tr>

        <td class="client_detailview_label_1">
        <label for=" " title=" " >保单号</label>
        </td>
        <td class="client_detailview_value_1" colspan="3"><input type="text" name="vin" value="{$CURLSTR['data']['policy_no']}" ui="5" mandatory="1" label="保单号" id="vin" class="responsive_width_98" readonly></td>

    </tr>

    <tr>
        <td class="client_detailview_label_1" colspan="2">
            <label for=" " title=" " >保额</label>
        </td>

        <td class="client_detailview_label_1" colspan="2">
            <label for=" " title=" " >保费</label>
        </td>
    </tr>

    {foreach from=$CURLSTR['data']['items'] item=v}
    <tr>
        <td class="client_detailview_label_1" >
            <label for="" title=" " >{$v.item_name}</label>
        </td>
        <td class="client_detailview_label_1">
            <label for=" " title=" " ><input type="text" name="vin" value="{$v.amount}" ui="5" mandatory="1" label="{$v.item_name}" id="vin" class="responsive_width_98" readonly></label>
        </td>
        <td class="client_detailview_label_1" colspan="2">
            <label for=" " title=" " ><input type="text" name="vin" value="{$v.premium}" ui="5" mandatory="1" label="{$v.item_name}" id="vin" class="responsive_width_98" readonly></label>
        </td>
    </tr>
    {/foreach}
</table>
</form>
  {else}
      <div >
        <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
                <table><tr>
                <td>
                <span class="ui-icon ui-icon-info" ></span></td><td>
                <strong>对不起！</strong> 没有满足条件的记录可显示。
                </td></tr></table>
            </div>
        </div>
    </div>
{/if}
<script>
{literal}
  zswitch_ui_form_init("#detailview_account_track_add_dlg_form");
{/literal}
</script>