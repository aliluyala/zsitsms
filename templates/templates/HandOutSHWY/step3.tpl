<div  style="font-size:12px">
    <input type="hidden" id="editview_module_name" value="{$MODULE}"/>
    <table class="client_listview_table small" cellspacing="0">
                <tr>
                    <th>&nbsp;</th>
                    <th>执行结果</th>
                </tr>
        {if $COUNT}
            {if $OPERA eq 'handout' or $OPERA eq 'recycle'}
                <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.ACCOUNT}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.UPDATE}:{$COUNT.BASE}{$MOD.PEOPLE}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>&nbsp;</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.SKIP}:{$COUNT.SKIP}{$MOD.PEOPLE}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>&nbsp;</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.SKIPSIT}:{$COUNT.SKIPSIT}{$MOD.PEOPLE}</span>
                    </td>
                </tr>
                {if $OPERA eq 'recycle'}
                    {if $COUNT.TRACK}
                    <tr>
                        <td class="client_detailview_label_1">
                            <label>{$MOD.TRACK}</label>
                        </td>
                        <td class="client_detailview_value_1">
                            <span>{$MOD.DELETE}:{$COUNT.TRACK}{$MOD.RECORDS}</span>
                        </td>
                    </tr>
                    {/if}
                    {if $COUNT.POLICY}
                    <tr>
                        <td class="client_detailview_label_1">
                            <label>{$MOD.POLICY}</label>
                        </td>
                        <td class="client_detailview_value_1">
                            <span>{$MOD.DELETE}:{$COUNT.POLICY}{$MOD.RECORDS}</span>
                        </td>
                    </tr>
                    {/if}
                {/if}
                <!-- <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.TELEMARKET}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.NEW}:{$COUNT.telemarket.new}{$MOD.RECORDS}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>&nbsp;</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.UPDATE}:{$COUNT.telemarket.update}{$MOD.RECORDS}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>&nbsp;</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.SKIP}:{$COUNT.telemarket.skip}{$MOD.RECORDS}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>&nbsp;</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.SKIPSIT}:{$COUNT.telemarket.skipSit}{$MOD.RECORDS}</span>
                    </td>
                </tr> -->
            {elseif $OPERA eq 'delete'}
                <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.ACCOUNT}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.DELETE}:{$COUNT.BASE}{$MOD.RECORDS}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.TRACK}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.DELETE}:{$COUNT.TRACK}{$MOD.RECORDS}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.POLICY}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.DELETE}:{$COUNT.POLICY}{$MOD.RECORDS}</span>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.REVISIT}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.DELETE}:{$COUNT.revisit}{$MOD.PEOPLE}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.PURCHASE}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.DELETE}:{$COUNT.purchase}{$MOD.PEOPLE}</span>
                    </td>
                </tr>
                <tr>
                    <td class="client_detailview_label_1">
                        <label>{$MOD.TELEMARKET}</label>
                    </td>
                    <td class="client_detailview_value_1">
                        <span>{$MOD.DELETE}:{$COUNT.telemarket}{$MOD.RECORDS}</span>
                    </td>
                </tr> -->
            {/if}
        {else}
            <tr>
                <td class="client_detailview_label_1">
                    <label>操作失败!</label>
                </td>
            </tr>
        {/if}
    </table>
</div>