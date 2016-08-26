<script type="text/javascript" src="js/utils.js?{$V}"></script>

<h1>{$Title}</h1>

<div style="padding: 2px 0 4px;">
    <table>
        <tr>
            <td><a href="event.php" class="Button Small">Add Event</a></td>
        </tr>
    </table>
</div>

<script type="text/javascript">{literal}
$(document).ready(function(){
	imagePreview();
});
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div style="padding: 8px 0 0;">
<table class="T-panel">
<col style="width: 50px;" />
<col style="width: 200px;" />
<col style="width: 35px;" />
<col/>
<col style="width: 160px;" />
<tr>
    <th></th>
    <th><div style="position: relative;">Name <form action="events.php" method="get"><input type="search" class="F-search" name="search" value="{$Search|escape}" placeholder="search" results="8" accesskey="s" /></form></div></th>
    <th></th>
    <th>Description</th>
    <th>Date</th>
</tr>
{foreach from=$Events item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><span><img src="{if $row.event_image == ''}/i/decor/placeholder-article.png{else}/thumbnails/events/{$row.event_image}.jpg{/if}" class="Thumbnail" alt="" width="42" height="26"/></span></td>
        <td><div class="Trunc"><a href="event.php?id={$row.event_id}">{$row.event_name|escape}</a></div></td>
        <td>{if $row.geo_tag_id != 0}<img src="/site/i/icons/marker-small.png" alt="" width="16" height="16" />{/if}</td>
        <td><div class="Trunc">{$row.event_description|escape}</div></td>
        <td><div>{$row.event_date|date_format:"%d-%b-%Y %I:%M %p"}</div></td>
    </tr>
{/foreach}
</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>