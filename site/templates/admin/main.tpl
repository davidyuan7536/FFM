<!DOCTYPE html>
<html>
<head>
    <title>{$Title} - Control Panel</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="default.css?0.1"/>
{if $smarty.const.__FFM_NAME__=='PROD'}
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="icon" href="/i/favicon32.png" sizes="32x32" type="image/png" />
{else}
<link rel="shortcut icon" href="/i/favicon16-{$smarty.const.__FFM_NAME__}.png?{$V}" />
<link rel="icon" href="/i/favicon16-{$smarty.const.__FFM_NAME__}.png?{$V}" type="image/png" />
<link rel="icon" href="/i/favicon32-{$smarty.const.__FFM_NAME__}.png?{$V}.png" sizes="32x32" type="image/png" />
{/if}
    <script type="text/javascript" src="/js/sources/jquery.js?{$V}"></script>
    <script type="text/javascript">{literal}
        var requests = 0;
        
        $(document).ready(function() {
            $('#Loading').ajaxSend(function(e, r, s) {
                requests++;
                $(this).show();
            }).ajaxComplete(function(e, r, s) {
                requests--;
                if (requests == 0) {
                    $(this).fadeOut('fast');
                }
            });

            $("#Error").ajaxError(function(e, r, s) {
                $("#ErrorText").text("Error requesting");
                $(this).fadeIn('fast');
            }).ajaxSend(function(e, r, s) {
                $(this).hide();
            }).click(function() {
                $(this).fadeOut('fast');
            });
        });
    {/literal}</script>
</head>
<body>
<div class="page">
    <div class="header">
        <h1><a href="/site/admin/"><img src="/i/{if $smarty.const.__FFM_NAME__=='PROD'}favicon32.png{else}favicon32-{$smarty.const.__FFM_NAME__}.png?{$V}.png{/if}" alt="" width="32" height="32" /></a> {$smarty.const.__FFM_NAME__}</h1>
    </div>
    <div class="menu">
        <ul class="menu-group">
            <li class="menu-item{if $Section == 'dashboard'} menu-selected{/if}"><a href="index.php">Dashboard</a></li>
            <li class="menu-item{if $Section == 'comments'} menu-selected{/if}"><a href="comments.php">Comments</a></li>
            <li class="menu-item{if $Section == 'options'} menu-selected{/if}"><a href="options.php">Options</a></li>
            <li class="menu-item{if $Section == 'about'} menu-selected{/if}"><a href="about.php">About</a></li>
        </ul>
        <hr/>
        <ul class="menu-group">
            <li class="menu-item{if $Section == 'artists'} menu-selected{/if}"><a href="artists.php">Artists</a></li>
            <li class="menu-item{if $Section == 'articles'} menu-selected{/if}"><a href="articles.php">Articles</a></li>
            <li class="menu-item{if $Section == 'videos'} menu-selected{/if}"><a href="videos.php">Video</a></li>
            {*<li class="menu-item{if $Section == 'events'} menu-selected{/if}"><a href="events.php">Events</a></li>*}
            <li class="menu-item{if $Section == 'label'} menu-selected{/if}"><a href="label.php">Label</a></li>
        </ul>
        <hr/>
        <ul class="menu-group">
            <li class="menu-item{if $Section == 'audio'} menu-selected{/if}"><a href="audio.php">Audio</a></li>
            <li class="menu-item{if $Section == 'pictures'} menu-selected{/if}"><a href="pictures.php">Pictures</a></li>
        </ul>
        <hr/>
        <ul class="menu-group">
            {*<li class="menu-item"><span href="#">Dictionary</span></li>*}
            <li class="menu-item{if $Section == 'genres'} menu-selected{/if}"><a href="genres.php">Genres</a></li>
            <li class="menu-item{if $Section == 'geotags'} menu-selected{/if}"><a href="geotags.php">Geotags</a></li>
            <li class="menu-item{if $Section == 'queue'} menu-selected{/if}"><a href="queue.php">Geotag queue</a></li>
        </ul>
        <hr/>
        <ul class="menu-group">
            <li class="menu-item{if $Section == 'users'} menu-selected{/if}"><a href="users.php">Users</a></li>
            <li class="menu-item{if $Section == 'requests'} menu-selected{/if}"><a href="requests.php">Requests</a></li>
            <li class="menu-item{if $Section == 'promoters'} menu-selected{/if}"><a href="promoters.php">Promoters</a></li>
            <li class="menu-item{if $Section == 'rating'} menu-selected{/if}"><a href="rating.php">Rating</a></li>
        </ul>
        <hr/>
        <ul class="menu-group menu-extra">
            <li class="menu-item"><a href="http://{$smarty.const.__FFM_HOST__}/" target="_blank">{$smarty.const.__FFM_HOST__}</a></li>
        </ul>
    </div>
    <div class="frame">
        <div class="A-loading" id="LoadingSecondary" style="display: none;"></div>
        <div class="A-loading" id="Loading" style="display: none;"></div>
        <div class="A-message" id="Message" style="display: none;">
            <img src="/site/i/icons/tick.png" alt="" width="24" height="24" />
            <span id="MessageText"></span>
        </div>
        <div class="A-error" id="Error" style="display: none;">
            <img src="/site/i/icons/exclamation.png" alt="" width="24" height="24" />
            <span id="ErrorText"></span>
        </div>
        {include file="admin/$Template.tpl"}
    </div>
    <div class="clear"></div>
    <div class="footer">
        2010&ndash;2011 &copy; <a href="http://www.creasence.com">Creasence</a> | <a href="mailto:support@creasence.com">Support</a>
    </div>
</div>
</body>
</html>

