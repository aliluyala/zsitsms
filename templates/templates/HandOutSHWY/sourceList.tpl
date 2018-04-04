<table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
    <tr>
        <th>登录名</th>
        <th>姓名</th>
        <th>拥有名单数</th>
    </tr>
    {foreach from=$SOURCELIST item=list}
        <tr>
            <td>{$list.user_name}</td>
            <td>{$list.name}</td>
            <td>{$list.sum}</td>
        </tr>
    {/foreach}
</table>