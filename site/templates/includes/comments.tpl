<div style="padding-top: 10px;">
{if $USER}
    <div style="float: left;width: 73px;"><img src="/i/decor/placeholder-user_s.png" width="50" height="50"></div>
    <div style="float: left;width: 570px;">
        <div style="background-color: #f8f9fa;border: 1px solid #d5d9de;padding: 10px 15px;">
            <b>{$USER['user_name']}</b>
            <form id="CommentsForm">
                <input type="hidden" id="CommentCategory" value="{$CommentCategory}">
                <input type="hidden" id="CommentParentId" value="{$CommentParentId}">
                <div><textarea class="F-text" id="CommentText" placeholder="{$LANG.comment.text}" style="width: 100%; height: 100px;margin: 10px 0;"></textarea></div>
                <div><input type="submit" value="Comment" class="F-button right"></div>
                <div class="clear"></div>
            </form>
            <script type="text/javascript">{literal}
                $('#CommentsForm').submit(function() {
                    var data = {
                        'Action': 'PostComment',
                        'CommentCategory': $('#CommentCategory').val(),
                        'CommentParentId': $('#CommentParentId').val(),
                        'CommentText': $('#CommentText').val()
                    };
                    $.post('/accounts/', data, function(result) {
                        if (result['message']) {
                            alert (result['message']);
                            $('#CommentText').focus();
                        } else {
                            if (result['elements']) {
                                $.each(result['elements'], function(i, v) {
                                    $(i).html(v);
                                });
                                $('#CommentText').val('').focus();                                
                            }
                        }
                    }, 'json');
                    return false;
                });
            {/literal}</script>
        </div>
    </div>
{else}
    <div style="float: left;width: 73px;"><span>&nbsp;</span></div>
    <div style="float: left;width: 570px;">
        <div style="background-color: #f8f9fa;color: #6a6d71;border: 1px solid #d5d9de;padding: 10px 15px;">
            {$LANG.comment.login}
            <br>
            <a href="/" onclick="return popupLogin();" style="color: #343434;">{$LANG.menu.login}</a>
            <span> / </span>
            <a href="/" onclick="return popupRegister();" style="color: #343434">{$LANG.menu.register}</a>
        </div>
    </div>
{/if}
<div class="clear"></div>
</div>

<div id="CommentsList">
    {include file='includes/comment_list.tpl'}
</div>