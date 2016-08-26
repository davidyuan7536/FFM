<script type="text/javascript" src="js/utils.js?{$V}"></script>

<div style="float: right;"><a href="/site/help/video.html" target="_blank">Help</a></div>

<h1>{$Title}</h1>

<div style="padding: 2px 0 4px;">
    <table>
        <tr>
            <td><a href="video.php" class="Button Small">Add Video</a></td>
        </tr>
    </table>
</div>

<script type="text/javascript">{literal}
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div style="padding: 8px 0 0;">
<table class="T-panel">
<col />
<col style="width: 22px;" />
<col style="width: 80px;" />
<col style="width: 150px;" />
<col style="width: 150px;" />
<tr>
    <th>Name</th>
    <th></th>
    <th>Service</th>
    <th><div style="position: relative;">Id <form action="videos.php" method="get"><input type="search" class="F-search" name="Search" value="{$Search|escape}" placeholder="Search by Id" results="8" accesskey="s" /></form></div></th>
    <th></th>
</tr>
{foreach from=$Videos item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><div class="Trunc"><a href="video.php?id={$row.video_id}">{$row.video_name}</a></div></td>
        <td><img src="/site/i/icons/favicon-{$row.service_name}.gif" alt="" width="16" height="16" style="vertical-align: -2px;" /></td>
        <td><div class="Trunc">{$row.service_name}</div></td>
        <td><div class="Trunc Small">{$row.service_id}
            <a href="http://{if $row.service_name == 'youtube'}www.youtube.com/watch?v={$row.service_id}{else}www.vimeo.com/{$row.service_id}{/if}"
                target="_blank"><img src="/site/i/icons/external-small.png" alt="" width="16" height="16" style="vertical-align: -3px;" /></a></div></td>
        <td><div class="Trunc Small">{if $row.artist_id != 0}<a href="/site/admin/artist.php?id={$row.artist_id}"><img src="/site/i/icons/xfn-friend.png" width="16" height="16" alt="" /> {$row.artist.name|escape}</a>{/if}</div></td>
    </tr>
{/foreach}
</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>