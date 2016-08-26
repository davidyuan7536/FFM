<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>

<h1>{$Title}</h1>

<div style="padding: 8px 0 0;">
<table class="T-panel">
<col style="width: 100px;" />
<col style="width: 100px;" />
<col style="width: 100px;" />
<col style="width: 100px;" />
<col style="width: 18px;" />
<tr>
    <th>User</th>
    <th>Artist</th>
    <th>Promoter</th>
    <th>Event</th>
    <th></th>
    <th>Message</th>
</tr>
{foreach from=$Logs item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><div class="Trunc Small"><a href="/site/admin/user.php?id={$row.user_id}"><img src="/site/i/icons/xfn-sweetheart.png" width="16" height="16" alt="" /> {$row.user_email|escape}</a></div></td>
        <td><div class="Trunc Small">{if !empty($row.artist_id)}<a href="/site/admin/artist.php?id={$row.artist_id}"><img src="/site/i/icons/xfn-friend.png" width="16" height="16" alt="" /> {$row.artist_name|escape}</a>{/if}</div></td>
        <td><div class="Trunc Small">{if !empty($row.promoter_id)}<a href="/promoters/{$row.promoter_filename}.html" target="_blank"><img src="/site/i/icons/xfn-promoter.png" width="16" height="16" alt="" /> {$row.promoter_name|escape}<img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a>{/if}</div></td>
        <td><div class="Trunc Small">{if !empty($row.event_id)}<a href="/site/admin/event.php?id={$row.event_id}">{$row.event_name|escape}</a>{/if}</div></td>
        <td><img src="/site/i/icons/notes.png" width="16" height="16" class="LogImage" message="{$row.message|escape}" style="cursor: pointer;" /></td>
        <td><div class="Trunc Small">{$row.message|truncate:512:"...":true|escape}</div></td>
    </tr>
{/foreach}
</table>
</div>

<script type="text/javascript">{literal}
imagePreview();
$('.LogImage').click(function() {
    var m = $(this).attr('message');
    var d = $('<div/>').dialog({
        'modal': true,
        'width': 700,
        'height': 500
    });
    $('<textarea/>').css({'width':'100%','height':'420px','background':'#eee','border':'1px solid #aaa'}).val(m).appendTo(d).focus();
});
{/literal}</script>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>