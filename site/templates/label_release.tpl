<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
    <meta property="og:title" content="{$Release.name|escape}" />
    <meta property="og:url" content="http://{$HOST}/artists/{$Release.filename}.html" />
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
    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
    <script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing,places"></script>
    <script type="text/javascript" src="/js/maps.js?{$V}"></script>
</head>

<body>
{assign var=url value="http://{$HOST}/artists/{$Release.filename}.html"}
{assign var=title value="{$Release.name|escape}"}
{include file='includes/global_top.tpl' Share="true"}

<div class="global-content">

    <div class="C-left">
        <div class="Release-header">
            <img src="/i/decor/ffm-music-label.png">
            <h1>{if $LANG.id == 'ru' && $Release.title_ru}{$Release.title_ru}{else}{$Release.title}{/if}</h1>
            {if $Release.ffm_id}
                <div class="Label-release-id">{strtoupper($Release.ffm_id)}</div>
            {/if}
        </div>
        <div class="A-tags">
            <span class="H-genres">
                {$Release.date|date_format:"%B %e, %Y"} |
                {foreach from=$Release.genres item=irow name=iGenres}
                    <a href="/artists/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
                {/foreach}
            </span>
        </div>

        <div class="Release-container">
            <div class="Release-player">
                {$Release.player_for_page}
            </div>
            <div class="Release-information">
                <div class="Release-description">
                    {if $LANG.id == 'ru' && $Release.description_ru}{strip_tags($Release.description_ru, '<a>')}{else}{strip_tags($Release.description, '<a>')}{/if}
                </div>
                <div class="Release-download"><a href="{$Release.download_link}">{$LANG.release.downloadOnBandcamp}</a></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="C-right">
        <div class="social-button-container-wrapper">
            <div class="social-button-container">
                <fb:like href="http://{$HOST}/artists/{$Release.filename}.html" layout="button_count" width="110" font=""></fb:like>
            </div>
            <div class="social-button-container">
                <div id="vk_like" style="width: 80px;"></div>
                <script type="text/javascript">
                    {literal}
                    VK.Widgets.Like("vk_like", {"type": "mini", "width": 80});
                    {/literal}
                </script>
            </div>
            <div class="social-button-container">
                <a href="https://twitter.com/share" class="twitter-share-button" data-size="100"></a>
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
            </div>
        {/if}
        <div class="Release-artists">
            <h2 class="Release-artists-header">{$LANG.headers.releaseArtists}</h2>
            {assign var="artistsList" value=$Release['artists']}
            {include file='artist_list.tpl'}
            {if count($Release['artists']) > 5}
                <div class="Release-artists-show-all">{$LANG.label.showAllArtists}</div>
                <div class="Release-artists-hide">{$LANG.label.hideArtists}</div>
            {/if}
        </div>
    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
<script>
    jQuery(document).ready(function($) {
        var $releaseArtistsWrapper = $('.Release-artists .Release-artists-wrapper');
        var releaseArtistsWrapperHeight = $releaseArtistsWrapper.height();

        $('.Release-artists-show-all').click(function() {
            $releaseArtistsWrapper.height('auto');

            $(this).hide();
            $('.Release-artists-hide').show();
        });

        $('.Release-artists-hide').click(function() {
            $releaseArtistsWrapper.height(releaseArtistsWrapperHeight);

            $(this).hide();
            $('.Release-artists-show-all').show();
        });

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
    });
</script>
</body>
</html>
