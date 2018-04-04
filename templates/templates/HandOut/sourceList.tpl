<table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
    <tr>
    {foreach $LISTVIEW_HEADERS as $name => $col }
        <th>
            {$col.label}
        </th>
    {/foreach}
    </tr>
    {foreach $LISTVIEW_DATA as $id => $row}
        <tr>
            {foreach $row as $fieldname => $field}
                <td>
                    {$field.value}
                </td>
            {/foreach}
        </tr>
    {/foreach}
</table>