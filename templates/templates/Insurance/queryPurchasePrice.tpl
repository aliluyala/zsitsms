{if $VEHICLEINFO.total neq 0}
    <div id="vehicleinfo">
        <table border="0" cellspacing="0"  style="text-align:center">
        <tr><td style="font-weight:bold">搜索:</td>
            <td><input id="serach_input" type="text" value="{$SEARCH_VALUE}"/>

            </td>
            <td><a href="javascript:void(0)"
                    onclick="VehicleInfo.search(1,'');" title="搜索">
                    <img src="{$IMAGES}/search_lense.png" style="width:16px; height:16px;border:none"/>
                </a>
            </td>
        </tr>
        </table>
        <table  cellspacing="0"  style="width:100%;text-align:right;border-style:solid none none none;  border-width:1px;border-color:#ACACAC;">
        <tr>
            <td >
                <a href="javascript:void(0)" style="color:#1E90FF;font-size:12px" title="向前翻一页"
                onclick="VehicleInfo.search({$VEHICLEINFO.prePage},'{$SEARCH_VALUE}');"
                >前一页</a>
                <label>{$VEHICLEINFO.page}/{$VEHICLEINFO.totalPage}(总条数:{$VEHICLEINFO.total})</label>
                <a href="javascript:void(0)" style="color:#1E90FF;font-size:12px" title="向后翻一页"
                onclick="VehicleInfo.search({$VEHICLEINFO.nextPage},'{$SEARCH_VALUE}');"
                >后一页</a>
            </td>
        </tr>
        </table>
        <table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
            <tr>
                <th>#</th>
                <th>车型代码</th>
                <th>车型名称/别名</th>
                <th>生产厂家</th>
                <th>排气量(ML)</th>
                <th>新车购置价</th>
                <th>新车购置价(含税)</th>
                <th>类比车型价</th>
                <th>类比车型价(含税)</th>
                <th>吨位</th>
                <th>上市年份</th>
                <th>备注</th>
            </tr>
            {foreach from=$VEHICLEINFO.vhlList key=i item=list}
                <tr>
                    <td><input type="radio" value="{$list.szxhTaxedPrice},{$list.vehicleDisplacement},{$list.vehicleSeat}" name="buyprice"></td>
                    <td>{$list.vehicleId}</td>
                    <td>{$list.vehicleName}</td>
                    <td>{$list.vehicleMaker}</td>
                    <td>{$list.vehicleDisplacement}</td>
                    <td>{$list.vehiclePrice}</td>
                    <td>{$list.szxhTaxedPrice}</td>
                    <td>{$list.nXhKindpriceWithouttax}</td>
                    <td>{$list.xhKindPrice}</td>
                    <td>{$list.vehicleTonnage}</td>
                    <td>{$list.vehicleYear}</td>
                    <td>{$list.vehicleRemark}</td>
                </tr>
            {/foreach}
        </table>
        <script language="javascript">
            zswitch_client_listview_table_init("client_listview_table");
        </script>
    </div>
{else}
    <div >
        <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
                <table><tr>
                <td>
                <span class="ui-icon ui-icon-info" ></span></td><td>
                <strong>对不起！</strong> 没有满足条件的记录可显示，请重新确认车型。
                </td></tr></table>
            </div>
        </div>
    </div>
{/if}