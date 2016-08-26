<!DOCTYPE html>
<html lang="{$LANG.id}" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<meta property="og:title" content="{$Track.track_name|replace:'"':'&quot;'}" />
<meta property="og:type" content="song" />
<meta property="og:url" content="http://{$HOST}/media/t/{$Track.track_id}/" />
<meta property="og:image" content="{if $Release.release_image == 0}http://{$HOST}/i/decor/placeholder-release_b.png{else}http://{$HOST}{$smarty.const.__FFM_ARCHIVE_FRONT__}{$Release.release_hash}/cover_b.jpg?{$Release.release_image}{/if}" />
<meta property="og:audio" content="http://{$HOST}{$smarty.const.__FFM_ARCHIVE_FRONT__}{$Release.release_hash}/{$Track.track_filename|escape:'url'}" />
<meta property="og:audio:title" content="{$Track.track_name|replace:'"':'&quot;'}" />
<meta property="og:audio:artist" content="{$Artist.name|replace:'"':'&quot;'}" />
<meta property="og:audio:album" content="{$Release.release_name|replace:'"':'&quot;'}" />
<meta property="og:audio:type" content="application/mp3" />
<meta property="og:site_name" content="{$LANG.global.title}" />
<meta property="fb:app_id" content="{$smarty.const.__FFM_FBID__}" />
<meta property="fb:admins" content="{$smarty.const.__FFM_ADMIN__}" />
{include file='includes/global_head.tpl'}
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    <div class="C-left">

        <iframe src="/media/t/{$Track.track_id}/embed" frameborder="0" scrolling="no" width="645" height="130" marginwidth="0" marginheight="0"></iframe>

    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
