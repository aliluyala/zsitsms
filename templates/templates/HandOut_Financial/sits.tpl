<select id="available_fields" multiple="" size="10" name="available_fields" class="txtBox" style="width: 100%;min-width: 90px;">
{if $SIT}
    {foreach from=$SIT item=sit}
        <option value="{$sit.id}">{$sit.user_name}({$sit.name})</option>
    {/foreach}
{/if}
</select>