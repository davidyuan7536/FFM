<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>

<h1>{$Title}</h1>

<script type="text/javascript">{literal}
$(document).ready(function(){
    imagePreview();
    $('.CommentImage').click(function() {
        var m = $(this).attr('message');
        var d = $('<div/>').dialog({
            'modal': true,
            'width': 700,
            'height': 500
        });
        $('<textarea/>').css({'width':'100%','height':'420px','background':'#eee','border':'1px solid #aaa'}).val(m).appendTo(d).focus();
    });
    $('button[value="Delete"]').click(function() {
        var cid = $(this).attr('cid');
        if (confirm("Do you really want to delete this comment?")) {
            var data = {
                'Action': 'Delete',
                'Id': cid
            };
            $.post('/site/admin/comments.php', data, function(result) {
                if (result['status'] == 'OK') {
                    window.location.reload();
                } else {
                    alert (result['message']);
                }
            }, 'json');
        }
    });
});
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div style="">
<table class="T-panel">
<col />
<tr>
    <th width="120">User</th>
    <th width="100">Object</th>
    <th width="20"></th>
    <th>Message</th>
    <th width="75"></th>
</tr>
{foreach from=$Comments item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><div class="Trunc Small"><a href="/site/admin/user.php?id={$row.user_id}"><img src="/site/i/icons/xfn-sweetheart.png" width="16" height="16" alt="" /> {$row.user_email|escape}</a></div></td>
        <td><div class="Trunc Small">{if $row.comment_category == $smarty.const.COMMENT_CATEGORY_ARTICLE}<a href="/articles/{$row.object.filename}.html#comments" target="_blank">{$row.object.title|escape}<img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a>
                {elseif $row.comment_category == $smarty.const.COMMENT_CATEGORY_ARTIST}<a href="/artists/{$row.object.filename}.html#comments" target="_blank">{$row.object.name|escape}<img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a>
                {elseif $row.comment_category == $smarty.const.COMMENT_CATEGORY_PROMOTER}<a href="/promoters/{$row.object.promoter_filename}.html#comments" target="_blank">{$row.object.promoter_name|escape}<img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a>
            {/if}
            </div>
        </td>
        <td><img src="/site/i/icons/notes.png" width="16" height="16" class="CommentImage" message="{$row.comment_text|escape} // {$row.comment_date|date_format:"%B %e, %Y %H:%M"}" style="cursor: pointer;" /></td>
        <td><div class="Trunc Small">{$row.comment_text|truncate:512:"...":true|escape}</div></td>
        <td>
            <button type="button" value="Delete" cid="{$row.comment_id}" style="font-size: 9px;" class="Button Small">Delete</button>
        </td>
    </tr>
{/foreach}

</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>

