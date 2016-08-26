<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<meta property="og:title" content="{$Artist.name|escape}" />
<meta property="og:type" content="musician" />
<meta property="og:url" content="http://{$HOST}/artists/{$Artist.filename}.html" />
<meta property="og:image" content="http://{$HOST}{$Artist|artist_picture:"b"}" />
<meta property="og:site_name" content="{$LANG.global.title}" />
<meta property="vk:app_id" content="{$smarty.const.__FFM_VKID__}" />
<meta property="fb:app_id" content="{$smarty.const.__FFM_FBID__}" />
<meta property="fb:admins" content="{$smarty.const.__FFM_ADMIN__}" />
<link rel="stylesheet" type="text/css" href="/i/lightbox/lightbox.css?{$V}" media="all" />
{include file='includes/global_head.tpl'}
<script type="text/javascript" src="/i/lightbox/lightbox.js?{$V}"></script>
<script type="text/javascript" src="/js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="/js/artist.js?{$V}"></script>
{if $LANG.id == 'ru'}
<script type="text/javascript" src="/js/sources/jquery.ui.datepicker-ru.js?{$V}"></script>
{else}
<script type="text/javascript" src="/js/sources/jquery.ui.datepicker-en.js?{$V}"></script>
{/if}
{if $Editable}<script type="text/javascript" src="/js/artist_edit.js?{$V}"></script>{/if}
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing,places"></script>
<script type="text/javascript" src="/js/maps.js?{$V}"></script>
</head>

<body>
{assign var=url value="http://{$HOST}/artists/{$Artist.filename}.html"}
{assign var=title value="{$Artist.name|escape}"}
{include file='includes/global_top.tpl' Share="true"}

<div class="global-content">

    <div class="C-left">

<h1 class="H-artist">{$Artist.name}{if $Artist.name_ru != ''} <small>/ {$Artist.name_ru}</small>{/if}</h1>
        <div class="A-tags">
            {if $Country|@count > 0}<h3><a href="/artists/?region={$Country.filename}">{$Country.filename|upper}</a></h3>{/if}
            <span class="H-genres">
                {foreach from=$Artist.genres item=irow name=iGenres}
                    <a href="/artists/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
                {/foreach}
            </span>
        </div>

        <div class="A-card-wrap">
            <div class="A-card-image"><span class="A-frame"></span><img src="{$Artist|artist_picture}" alt="" width="130" height="130"/></div>
            <div class="A-card-info">
                <div class="A-text">{$Artist.description|escape|nl2br}</div>
                <div class="A-links">
                    <div class="A-links-ul">
                        {$Artist.links|escape|nl2br|links:'<span>$?</span>'}
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>

        <div id="tabs" style="min-height: 200px;">
            <ul>
                <li><a href="?tab=articles">{$LANG.artist.tabArticles}</a></li>
                <li><a href="?tab=videos"><span>{$LANG.artist.tabVideos}</span></a></li>
                <li><a href="?tab=photos"><span>{$LANG.artist.tabPhotos}</span></a></li>
                <li><a href="?tab=releases"><span>{$LANG.artist.tabReleases}</span></a></li>
            </ul>
        </div>
        <script type="text/javascript">{literal}
            $("#tabs").tabs(PROFILE_OPTIONS);
            {/literal}</script>
    </div>

    <div class="C-right">
        <div class="social-button-container-wrapper">
            <div class="social-button-container">
                <div id="vk_like"></div>
                <script type="text/javascript">
                    {literal}
                    VK.Widgets.Like("vk_like", {"type": "mini"});
                    {/literal}
                </script>
            </div>
            <div class="social-button-container">
                <fb:like href="http://{$HOST}/artists/{$Artist.filename}.html" layout="button_count" font=""></fb:like>
            </div>
            <div class="social-button-container">
                <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                <script>{literal}!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');{/literal}</script>
            </div>
        </div>
        {if !empty($GeoTag)}
            <div style="border-bottom: 1px solid #cbd0d6;">
                <input type="hidden" id="PlaceLat" value="{$NMPlace.place_lat}">
                <input type="hidden" id="PlaceLng" value="{$NMPlace.place_lng}">
                <input type="hidden" id="PlaceUrl" value="http://{$smarty.const.__NM_HOST__}/places/{$NMPlace.place_uuid}">
                <div id="PlaceMap" style="background: #fafafa; height: 280px; width: 280px;"></div>
                <div class="Wiki"><a href="{$GeoTag.wiki}" target="_blank">{$GeoTag.longname}</a></div>
                <script>
                    {literal}
                    var lat = $('#PlaceLat').val();
                    var lng = $('#PlaceLng').val();
                    var nmPlaceUrl = $('#PlaceUrl').val();
                    var Maps = maps();
                    var map = Maps.init($('#PlaceMap'), {
                        zoom: 15,
                        center: new google.maps.LatLng(lat, lng),
                        mapTypeControl: false,
                        readonly: true,
                        scrollwheel: true
                    });
                    map.setMarker(lat, lng, nmPlaceUrl);
                    {/literal}
                </script>
            </div>
        {/if}

        {if !empty($Audios)}
            <div id="musicbox" style="padding: 0 0 12px 0;">
                <h2>{$LANG.headers.audio}</h2>

                {foreach from=$Audios item=row}
                    <div class="player-wrap">
                        <div class="player embed">
                            <div class="player-name">
                                {if $row.artist}
                                    <a href="/artists/{$row.artist.filename}.html">{$row.artist.name}</a>
                                {/if}
                                {if $row.artist && $row.audio_name}&ndash;{/if} {$row.audio_name}
                            </div>
                            <div class="player-source"><a href="{$smarty.const.__FFM_AUDIO_FRONT__}{$row.audio_filename}" class="player-mp3"><span>{$row.audio_filename}</span></a></div>
                        </div>
                    </div>
                {/foreach}
            </div>
            <script type="text/javascript">{literal}
                $('.embed').each(function() {
                    GlobalPlayer.embed(this);
                });
            {/literal}</script>
        {/if}

    </div>
    
    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
