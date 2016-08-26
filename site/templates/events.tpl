<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
<script type="text/javascript">{literal}
$(document).ready(function() {
    $('#promo-wrap').before('<div id="articles-nav" class="articles-nav">').cycle({
        fx:      'scrollHorz',
        speed:   'fast',
        timeout: 8000,
        pager:   '#articles-nav'
    });

    var afisha;
    $('.afisha-row').each(function() {
        $(this).mouseenter(function() {
            $(this).addClass('afisha-over');
        }).mouseleave(function() {
            $(this).removeClass('afisha-over');
        });

        $(this).click(function() {
            if (afisha) {
                $(afisha).removeClass('afisha-open');
            }
            if (afisha == this) {
                afisha = null;
            } else {
                $(this).addClass('afisha-open');
                afisha = this;
            }
        });
    });
});
{/literal}</script>
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">
    <div class="rel">
        <div class="C-left">
            <h1>{$Title}</h1>

            <div id="promo-wrap" class="promo-wrap" style="border-bottom: none;">

                {foreach from=$PromoEvents item=row}
                <div class="promo-card" style="display: none;">
                    <div class="promo-image">
                        {*<div class="promo-image-sticker">Alla Farmer</div>*}
                        <img src="{if $row.event_image == ''}/i/decor/placeholder-article.png{else}/thumbnails/events_big/{$row.event_image}.jpg{/if}" alt="" width="405" height="300" />
                    </div>

                    <div class="promo-content">
                        <h4>{$row.event_date|date_format:"%e %B"|date_ru}</h4>
                        <div class="promo-title">{$row.event_name|escape}</div>
                        <div class="promo-subtitle">{$row.event_date|date_format:"%A"|date_ru}, в {$row.event_date|date_format:"%H:%M"}</div>
                        <div class="promo-text">{$row.event_description|escape|links:''|nl2br}</div>
                        <div class="promo-text">{$row.event_address|escape|links:''|nl2br}</div>
                    </div>
                    <div class="clear"></div>
                </div>
                {/foreach}

            </div>
        </div>

        <div class="C-right">
            {if !empty($Audios)}
                <div id="musicbox" style="padding: 0;">
                    <h2>{$LANG.headers.audio}</h2>

                    {foreach from=$Audios item=row}
                        <div class="player-wrap">
                            <div class="player embed">
                                <div class="player-name">
                                    {if $row.artist}
                                        <a href="/artists/{$row.artist.filename}.html">{$row.artist.name|escape}</a>
                                    {/if}
                                    {if $row.artist && $row.audio_name}&ndash;{/if} {$row.audio_name}
                                </div>
                                <div class="player-source"><a href="{$smarty.const.__FFM_AUDIO_FRONT__}{$row.audio_filename}" class="player-mp3"><span>{$row.audio_filename}</span></a></div>
                                {*<div class="player-genres"><a href="artists.html#/electronic">Electronic</a>, <a href="artists.html#/techno">Techno</a></div>*}
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

    <div class="C-left">
        {foreach from=$Events key=k item=v}
            <div class="afisha-wrap">
                <div class="afisha-header"><strong>{$v.date|date_format:"%e"}</strong> {$v.date|date_format:"%B"|date_ru}, {$v.date|date_format:"%A"|date_ru}</div>
                {foreach from=$v.events item=row}
                    <div class="afisha-row">
                        <div class="afisha-row-inner">
                            <div class="afisha-time">{$row.event_date|date_format:"%H:%M"}</div>
                            <div class="afisha-artist">
                                <div class="event-title">{$row.event_name|escape}</div>
                                {*<div class="event-genres"><a href="events.html#/rock">Rock</a>, <a href="events.html#/pop">Pop</a></div>*}
                                {if $row.event_image != ''}<div class="afisha-extra"><img src="/thumbnails/events/{$row.event_image}.jpg" alt="" width="122" /></div>{/if}
                            </div>
                            <div class="afisha-text">
                                {$row.event_description|escape|links:''|nl2br}
                                <div class="afisha-extra">
                                    {$row.event_address|escape|links:''|nl2br}
                                </div>
                            </div>
                            {*<div class="afisha-region">*}
                                {*<div class="event-region"><a href="events.html#ru">RU</a></div>*}
                            {*</div>*}
                            {if $row.geo_tag_id != 0}
                                <div class="afisha-place">
                                    <a href="/artists/?region={$row.geo_tag.filename}">{$row.geo_tag.name}</a>
                                </div>
                            {/if}
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div>{$event.event_name}</div>
                {/foreach}
            </div>
        {/foreach}
    </div>

    <div class="C-right">
        {if !empty($Videos)}
            <div style="padding: 12px 0 0;">
                <h2><a href="/video/">{$LANG.headers.video}</a></h2>

                {section name=row loop=$Videos}
                    <div style="padding-bottom: 16px;">
                        <div class="V-name">{if $Videos[row].artist_id != 0}<a href="/artists/{$Videos[row].artist.filename}.html">{$Videos[row].artist.name|escape}</a>
                            &ndash; {/if}<span title="{$Videos[row].video_name}">{$Videos[row].video_name}</span></div>
                        <div>
                            {if $Videos[row].service_name == 'youtube'}
                                <iframe src="http://www.youtube.com/embed/{$Videos[row].service_id}" width="280" height="180" frameborder="0"></iframe>
                                {elseif $Videos[row].service_name == 'vimeo'}
                                <iframe src="http://player.vimeo.com/video/{$Videos[row].service_id}?portrait=0&byline=0&title=0&color=ec0009" width="280" height="180" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"></iframe>
                            {/if}
                        </div>
                    </div>
                {/section}
            </div>
        {/if}
    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>