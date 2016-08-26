<script type="text/javascript" src="js/utils.js?{$V}"></script>

<div style="float: right;"><a href="/site/help/artists.html" target="_blank">Help</a></div>

<h1>{$Title}</h1>

<div style="padding: 2px 0 4px;">
    <table width="100%">
        <tr>
            <td><a href="artist.php" class="Button Small">Add Artist</a></td>
            <td align="right"><a href="/artists/new/{$WeekHash}">http://{$HOST}/artists/new/{$WeekHash}</a></td>
        </tr>
    </table>
</div>

<script type="text/javascript">{literal}
$(document).ready(function(){
	imagePreview();
});
{/literal}</script>

<style type="text/css">{literal}
.T-panel span img {
    display: block;
    border-radius: 20px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    margin-left: -2px;
}
.T-panel td {
    /*border-left: 1px solid #aaa;*/
}
{/literal}</style>

<div style="padding: 8px 0 0;">
<table class="T-panel">
<col style="width: 35px;" />
<col style="width: 180px;" />
<col style="width: 30px;" />
<col style="width: 37px;" />
<col style="width: 200px;" />
<col />
<tr>
    <th width="35"></th>
    <th colspan="2"><div style="position: relative;">Name <form action="artists.php" method="get"><input type="search" class="F-search" name="search" value="{$Search|escape}" placeholder="search" results="8" accesskey="s"/></form></div></th>
    <th>Geo</th>
    <th>Links</th>
    <th>Description</th>
</tr>
{foreach from=$Artists item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><span><img src="{$row|artist_picture:"s"}" class="Thumbnail" alt="" width="32" height="32"/></span></td>
        <td>
            <div class="Trunc"><a href="artist.php?id={$row.artist_id}">{$row.name|escape}</a></div>
            <small><div class="Trunc">{$row.name_ru|escape}</div></small>
        </td>
        <td><a href="/artists/{$row.filename}.html" target="_blank"><img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a></td>
        <td style="text-align: center;"><div>{if $row.geo_tag_id != 0}<img src="/site/i/icons/marker-small.png" alt="" width="16" height="16" style="vertical-align: -5px;" />{/if}</div></td>
        <td><small><div class="Trunc">{$row.links|escape}</div></small></td>
        <td><div class="Trunc">{$row.description|escape}</div></td>
    </tr>
{/foreach}
</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>