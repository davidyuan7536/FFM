{if empty($Events)}
    <div class="A-Events-row" style="padding: 10px 0;text-align: center;">{$LANG.artist.eventEmpty}</div>
{/if}
{foreach from=$Events item=row}
    <div class="A-Events-row" id="Row{$row.event_id}">
        <table width="100%">
        <colgroup>
            <col style="width: 32px;"/>
            <col style="width: 90px;"/>
            <col style="width: 125px;"/>
            <col />
            <col style="width: 130px;"/>
        {if $Editable}
            <col style="width: 75px;"/>
        {/if}            
        </colgroup>
        <tr style="vertical-align: top;">
            <td><div class="A-Events-day">{$row.event_date|date_format:"%e"}</div></td>
            {if $LANG.id == 'ru'}
                <td><div class="A-Events-date">{$row.event_date|date_format:"%B"|date_ru}, <br/>{$row.event_date|date_format:"%A"|date_ru}</div></td>
            {else}
                <td><div class="A-Events-date">{$row.event_date|date_format:"%B"}, <br/>{$row.event_date|date_format:"%A"}</div></td>
            {/if}
            <td rowspan="2"><div class="A-Events-title">{$row.event_name|escape}</div></td>
            <td rowspan="2"><div class="A-Events-text">{$row.event_description|escape|links:''|nl2br}</div></td>
            <td rowspan="2"><div class="A-Events-text">{$row.event_address|escape|links:''|nl2br}</div></td>
            {if $Editable}
                <td style="vertical-align: top;" rowspan="2">
                    <div style="padding-bottom: 4px;"><button class="F-button-mini A-Events-row-edit" value="{$row.event_id}">{$LANG.link.editMini}</button></div>
                    <div><button class="F-button-mini A-Events-row-delete" value="{$row.event_id}">{$LANG.link.delete}</button></div>
                </td>
            {/if}
        </tr>
        <tr>
            <td colspan="2">
                {if $row.event_image != ''}
                    <img src="/thumbnails/events/{$row.event_image}.jpg?{rand(100,999)}" alt="" width="110" />
                {/if}
                {*<img src="{if $row.event_image == ''}/i/decor/placeholder-article.png{else}/thumbnails/events/{$row.event_image}.jpg{/if}" alt="" width="122" />*}
            </td>
        </tr>
        </table>
    </div>
{/foreach}
{if $Editable && !empty($Events)}
<script type="text/javascript">{literal}initEventList();{/literal}</script>
{/if}
