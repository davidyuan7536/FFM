{foreach from=$Comments item=row name=comments}
    <div style="margin: 10px 0;">
        <div style="float: left;width: 73px;"><img src="/i/decor/placeholder-user_s.png" width="50" height="50"></div>
        <div style="float: left;width: 570px;">
            <div style="background-color: #f8f9fa;border: 1px solid #d5d9de;padding: 10px 15px;">
                <div style="font-size: 11px;float: right;color: #6a6d71">{$row['comment_date']|date_format:"%B %e, %Y %H:%M"}</div>
                <div><b>{$row['user_name']|escape}</b></div>
                <div style="padding-top: 10px;">{$row['comment_text']|escape|nl2br}</div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
{/foreach}
