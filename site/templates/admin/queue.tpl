<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>

<h1>{$Title}</h1>

<div style="padding: 8px 0 0;">
<table class="T-panel">
<col style="width: 35px;" />
<col style="width: 180px;" />
<col />
<tr>
    <th width="35"></th>
    <th>Name</th>
    <th>Geo</th>
</tr>
{foreach from=$Artists item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><span><img src="{$row|artist_picture:"s"}" class="Thumbnail" alt="" width="32" height="32"/></span></td>
        <td>
            <div class="Trunc"><a href="artist.php?id={$row.artist_id}">{$row.name|escape}</a></div>
            <small><div class="Trunc">{$row.name_ru|escape}</div></small>
        </td>
        <td>
            <div class="Trunc">{if $row.geo_tag_id != 0}<img src="/site/i/icons/marker-small.png" alt="" width="16" height="16" style="vertical-align: -5px;" />{/if}</div>
            <small><div class="Trunc">{$row.geo_tag_text|escape}</div></small>
        </td>
    </tr>
{/foreach}
</table>
</div>
