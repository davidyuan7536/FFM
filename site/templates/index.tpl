<!DOCTYPE html>
<!--

             ******** ******** ****     ****
            /**///// /**///// /**/**   **/**
            /**      /**      /**//** ** /**
            /******* /******* /** //***  /**
            /**////  /**////  /**  //*   /**
            /**      /**      /**   /    /**
            /**      /**      /**        /**
            //       //       //         //


-->
<html lang="{$LANG.id}" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<meta property="og:title" content="{$LANG.global.title}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://{$HOST}/" />
<meta property="og:image" content="http://{$HOST}/i/ffm.jpg" />
<meta property="og:site_name" content="{$LANG.global.title}" />
<meta property="fb:app_id" content="{$smarty.const.__FFM_FBID__}" />
<meta property="fb:admins" content="{$smarty.const.__FFM_ADMIN__}" />
{include file='includes/global_head.tpl'}
<meta name="google-site-verification" content="GKTAtpKvirmROIjNc5SNkJVoI9Uu3kgQ8jGiYczI8LM" />
<meta name="yandex-verification" content="4c67632154fb114e" />
<script type="text/javascript">{literal}
$(function() {
    $('#promo-wrap').before('<div id="articles-nav" class="articles-nav">').cycle({
        fx:      'scrollHorz',
        speed:   'fast',
        timeout: 8000,
        pager:   '#articles-nav'
    });

    $('.player').each(function() {
        GlobalPlayer.embed(this);
    });

    $('.Artist-Mini').miniPlayer('Artist-Mini');

});
{/literal}</script>
</head>

<body>
{assign var=url value="http://{$HOST}/"}
{assign var=title value="{$LANG.global.title}"}
{include file='includes/global_top.tpl' Share="true" Home="true"}

<div  class="global-content">
    <div class="home-top">

        <div class="C-left rel">
            <h1><a href="/articles/">{$LANG.headers.articles}</a></h1>

            <div id="promo-wrap" class="promo-wrap">

                {foreach from=$Articles.latest item=row}
                <div class="promo-card" style="display: none;">
                    <div class="promo-image">
                        {*<div class="promo-image-sticker">Alla Farmer</div>*}
                        <img src="{if $row.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles_big/{$row.image}.jpg{/if}" alt="" width="405" height="300" />
                    </div>

                    <div class="promo-content">
                        <div class="promo-title"><a href="/articles/{$row.filename}.html">{if $LANG.id == 'ru' && $row.title_ru}{$row.title_ru}{else}{$row.title}{/if}</a></div>
                        <div class="promo-text15">{if $LANG.id == 'ru' && $row.description_ru}{$row.description_ru}{else}{$row.description}{/if}</div>
                    </div>
                    <div class="clear"></div>
                </div>
                {/foreach}

            </div>
        </div>

        <div class="C-right" style="height: 366px; border-bottom: 1px solid #cbd0d6;">
            {if !empty($Audios)}
                <div id="musicbox" style="padding: 0;">
                    <h2 style="border-bottom: 1px solid #cbd0d6;">{$LANG.headers.audio}</h2>

                    <div style="padding-top: 10px;">
                    {foreach from=$Audios item=row}
                        <div class="player-wrap">
                            <div class="player embed">
                                <div class="player-name">
                                    {if $row.artist}
                                        <a href="/artists/{$row.artist.filename}.html">{$row.artist.name|escape}</a>
                                    {/if}
                                    {if $row.artist && $row.audio_name}&ndash;{/if} {$row.audio_name}
                                </div>
                                <div class="player-source"><a href="{$smarty.const.__FFM_AUDIO_FRONT__}{$row.audio_filename}" title="Download" class="player-mp3"><span>{$row.audio_filename}</span></a></div>
                                {*<div class="player-genres"><a href="artists.html#/electronic">Electronic</a>, <a href="artists.html#/techno">Techno</a></div>*}
                            </div>
                        </div>
                    {/foreach}
                    </div>
                </div>
                <div class="Podcast">
                    <a href="itpc://{$smarty.const.__FFM_HOST__}/podcast.xml">{$LANG.menu.podcast}</a>
                </div>
            {/if}
        </div>

        <div class="clear"></div>
    </div>

    <div class="home-middle">
        <h1><a href="/artists/">{$LANG.headers.artists}</a></h1>

        {include file='includes/widget-artists.tpl' ColumnCode='ru' ColumnTitle='Russia'}
        {include file='includes/widget-artists.tpl' ColumnCode='ua' ColumnTitle='Ukraine'}
        {include file='includes/widget-artists.tpl' ColumnCode='by' ColumnTitle='Belarus'}
        {include file='includes/widget-artists.tpl' ColumnCode='zz' ColumnTitle='Others'}

        <div class="clear"></div>
    </div>

     <div class="home-middle">
        <div class="left" style="width: 455px;margin-right: 35px;">
            <h1{if !empty($OPTIONS.new)} class="new"{/if}><span>{$LANG.headers.top}</span></h1>

            <div style="border-top: 1px solid #d4d9de;">
                {foreach from=$Top item=row name=top}
                    <div style="border-bottom: 1px solid #d4d9de;{if $smarty.foreach.top.iteration % 2 == 1}background-color: #fdfdfd;{/if}">
                        <div class="left" style="margin-right: 6px;padding: 4px 0;width: 20px; text-align: right;font-size: 14px;font-weight: bold;color: #7d8084;">{$smarty.foreach.top.iteration}</div>
                        <div class="left" style="padding-top: 4px;width: 220px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                            <div class="Artist-link"><a href="/artists/{$row.filename}.html">{$row.name|escape}</a></div>
                        </div>
                        <div class="left ellipsis" style="width: 170px;padding-top: 5px;font-size: 11px;" title="{$row.geo_tag.longname|escape}">
                            {$row.geo_tag.longname|escape}
                        </div>
                        <div class="right" style="padding-top: 3px;">
                            {if !empty($row.audio)}
                                <div class="Artist-Mini" title="{$row.audio.audio_name|escape}" url="{$smarty.const.__FFM_AUDIO_FRONT__}{$row.audio.audio_filename|escape:url}">
                                    <div class="Artist-Mini-Image"></div>
                                </div>
                            {/if}
                        </div>
                        <div class="clear"></div>
                    </div>
                {/foreach}
            </div>
        </div>
        <div class="left" style="width: 456px;">
            <h1><a href="/video/">{$LANG.headers.video}</a></h1>

            <div class="video-preview">
                <div style="padding-bottom: 10px;">
                    <div>
                        {if $Videos[0].service_name == 'youtube'}
                            <iframe src="http://www.youtube.com/embed/{$Videos[0].service_id}" width="460" height="250" frameborder="0"></iframe>
                        {elseif $Videos[0].service_name == 'vimeo'}
                            <iframe src="http://player.vimeo.com/video/{$Videos[0].service_id}?portrait=0&byline=0&title=0&color=ec0009" width="460" height="250" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"></iframe>
                        {/if}
                    </div>
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>

    <div class="home-middle" style="padding-bottom: 16px;">
        <h1><span>{$LANG.headers.genres}</span></h1>

        {include file='includes/widget-preview.tpl' FilterGenre='dance' FilterName='Dance'}
        {include file='includes/widget-preview.tpl' FilterGenre='electronic' FilterName='Electronic'}
        {include file='includes/widget-preview.tpl' FilterGenre='pop' FilterName='Pop'}
        {include file='includes/widget-preview.tpl' FilterGenre='rock' FilterName='Rock'}

        <div class="clear"></div>
    </div>

{*    {if !empty($Events)}
    <div class="home-bottom">
        <div>
            <h1><a href="/events/">{$LANG.headers.events}</a></h1>

            {section name=i loop=$Events}
                <div class="events-column">
                    <div class="event-wrap">
                        <div class="event-date">
                            <div class="event-day">{$Events[i].event_date|date_format:"%e"}</div>
                            <div class="event-month">{$Events[i].event_date|date_format:"%B"|date_ru},</div>
                            <div class="event-time">{$Events[i].event_date|date_format:"%H:%M"}</div>
                            <div class="event-region"><a href="events.html#ru">RU</a></div>
                        </div>
                        <div class="event-info">
                            <div class="event-title">{$Events[i].event_name|escape}</div>
                            {if $Events[i].event_image != ''}<div class="event-photo"><img src="/thumbnails/events/{$Events[i].event_image}.jpg" alt="" width="122" /></div>{/if}
                            <div class="event-text">{$Events[i].event_description|escape|links:''|nl2br}</div>
                            <div class="event-address">{$Events[i].event_address|escape|links:''|nl2br}</div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            {/section}

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    {/if}*}

    {if !empty($Releases)}
        <div class="home-bottom">
        <div class="Label-header">
            <img src="/i/decor/ffm-music-label.png" />
            <h1><a href="/label/">{$LANG.headers.label}</a></h1>
        </div>
        <div class="Label-container">
            <div class="A-row A-row-first">
                {section name=row loop=$Releases}
                <div class="Label-release">
                    <div class="Label-release-header">
                        <div class="Label-release-title">
                            <a href="/label/{$Releases[row].filename}.html">
                                {if $LANG.id == 'ru' && $Releases[row].title_ru}{$Releases[row].title_ru}{else}{$Releases[row].title}{/if}
                            </a>
                        </div>
                        <div class="Label-release-row">
                            <div class="Label-release-artist">
                                {if is_array($Releases[row].artist)}
                                    <a href="/artists/{$Releases[row].artist.filename}.html">{$Releases[row].artist.name}</a>
                                {else}
                                    {$LANG.label.variousArtists}
                                {/if}
                            </div>
                            {if $Releases[row].ffm_id}
                                <a class="Label-release-id" href="/label/{$Releases[row].filename}.html">{strtoupper($Releases[row].ffm_id)}</a>
                            {/if}
                        </div>
                    </div>
                    <div class="Label-release-player">
                        {$Releases[row].player_for_list}
                    </div>
                    <div class="Label-release-description">
                        {if $LANG.id == 'ru' && $Releases[row].description_ru}{strip_tags($Releases[row].description_ru, '<a>')}{else}{strip_tags($Releases[row].description, '<a>')}{/if}
                    </div>
                    <div class="Label-release-genres">
                        {assign var=genres value=$Releases[row].genres}
                        {foreach from=$genres item=genre name=genres}
                            <a href="/artists/?genre={$genre.filename}">{$genre.name}</a>{if !$smarty.foreach.genres.last}, {/if}
                        {/foreach}
                    </div>
                </div>
                {if $smarty.section.row.iteration % 4 == 0}
            </div><div class="A-row">
                {/if}
                {/section}
                <div class="clear"></div>
            </div>
        </div>
        </div>
    {/if}
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
