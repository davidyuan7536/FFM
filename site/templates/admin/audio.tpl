<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-artists.js?{$V}"></script>
<script type="text/javascript" src="js/audio.js?{$V}"></script>

<h1>{$Title}</h1>

<script type="text/javascript">{literal}
$(document).ready(function(){
    initSwfUploader('audio-upload.php');
});
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div class="W-wrap" style="margin: 8px 0 16px;" id="Uploader">
    <div id="placeholder"></div>
    <div id="progress"></div>
</div>

<div style="">
<table class="T-panel">
<col />
<tr>
    <th>Filename</th>
    <th><div style="position: relative;">Name <form action="audio.php" method="get"><input type="search" class="F-search" name="search" value="{$Search|escape}" placeholder="search" results="8" accesskey="s"/></form></div></th>
    <th>Album</th>
    <th>Artist</th>
</tr>
{foreach from=$Audio item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><div class="Trunc"><a href="audio-edit.php?id={$row.audio_id}">{$row.audio_filename}</a></div></td>
        <td><div class="Trunc">{$row.audio_name}</div></td>
        <td><div class="Trunc">{$row.audio_album}</div></td>
        <td><div class="Trunc Small">{if $row.artist_id != 0}<a href="/site/admin/artist.php?id={$row.artist_id}"><img src="/site/i/icons/xfn-friend.png" width="16" height="16" alt="" /> {$row.artist.name|escape}</a>{/if}</div></td>
    </tr>
{/foreach}
</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>

