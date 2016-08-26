<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>

<h1>{$Title}</h1>

<script type="text/javascript">{literal}
$(document).ready(function(){
	imagePreview();

    $('[alt="Approve"]').click(function() {
        var rid = $(this).attr('rid');
        $("#dialog-confirm-approve").dialog({
			resizable: false,
			height: 140,
			modal: true,
			title: 'Request #' + rid,
			buttons: {
				'Approve': function() {
                    var data = {
                        'Action': 'approve',
                        'Id': rid
                    };
                    $.post('/site/admin/requests.php', data, function(result) {
                        if (result['status'] == 'OK') {
                            window.location.reload();
                        } else {
                            $(this).dialog("close");
                            alert (result['message']);
                        }
                    }, 'json');
				},
				'Cancel': function() {
					$(this).dialog("close");
				}
			}
		});
    });
    $('[alt="Decline"]').click(function() {
        var rid = $(this).attr('rid');
        $("#dialog-confirm-decline").dialog({
			resizable: false,
			height: 140,
			modal: true,
			title: 'Request #' + rid,
			buttons: {
				'Decline': function() {
                    var data = {
                        'Action': 'decline',
                        'Id': rid
                    };
                    $.post('/site/admin/requests.php', data, function(result) {
                        if (result['status'] == 'OK') {
                            window.location.reload();
                        } else {
                            $(this).dialog("close");
                            alert (result['message']);
                        }
                    }, 'json');
				},
				'Cancel': function() {
					$(this).dialog("close");
				}
			}
		});
    });
});
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div style="">
<table class="T-panel">
<col />
<tr>
    <th width="50" style="text-align: right;">Id</th>
    <th width="120">User</th>
    <th width="100">Artist</th>
    <th>Message</th>
    <th width="50"></th>
</tr>
{foreach from=$Requests item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td style="text-align: right;"><div class="Trunc Small">{$row.request_id}</div></td>
        <td><div class="Trunc Small"><a href="/site/admin/user.php?id={$row.user_id}"><img src="/site/i/icons/xfn-sweetheart.png" width="16" height="16" alt="" /> {$row.user_email|escape}</a></div></td>
        <td><div class="Trunc Small"><a href="artist.php?id={$row.artist_id}"><img src="/site/i/icons/xfn-friend.png" width="16" height="16" alt="" /> {$row.name|escape}</a></div></td>
        <td><div class="Trunc Small">{$row.request_text|escape:'html'|truncate:256:'...'}

- {$row.request_email}
- {$row.request_date}</div></td>
        <td>
            <img src="/site/i/icons/tick-button.png" width="16" height="16" alt="Approve" rid="{$row.request_id}" style="cursor:pointer"/>
            <img src="/site/i/icons/cross-button.png" width="16" height="16" alt="Decline" rid="{$row.request_id}" style="cursor:pointer"/>
        </td>
    </tr>
{/foreach}

</table>
</div>

<div id="dialog-confirm-approve" title="" style="display: none;">
	<p><strong style="color:#0b0">Approve.</strong> Are you sure?</p>
</div>

<div id="dialog-confirm-decline" title="" style="display: none;">
	<p><strong style="color:#d00">Decline.</strong> Are you sure?</p>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>

