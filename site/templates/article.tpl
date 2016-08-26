<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<meta property="og:title" content="{$Article.title}" />
<meta property="og:type" content="article" />
<meta property="og:url" content="http://{$HOST}/articles/{$Article.filename}.html" />
<meta property="og:image" content="http://{$HOST}{if $Article.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$Article.image}.jpg{/if}" />
<meta property="og:site_name" content="{$LANG.global.title}" />
<meta property="vk:app_id" content="{$smarty.const.__FFM_VKID__}" />
<meta property="fb:app_id" content="{$smarty.const.__FFM_FBID__}" />
<meta property="fb:admins" content="{$smarty.const.__FFM_ADMIN__}" />
{include file='includes/global_head.tpl'}
<script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
<script type="text/javascript">{literal}
$(document).ready(function() {
    $('.embed').each(function() {
        GlobalPlayer.embed(this);
    });

    var musicbox = $('#musicbox');
    if (musicbox.length > 0) {
        var pos = musicbox.position().top;
        $(window).scroll(function () {
            var offset = $(document).scrollTop() - pos;
            if (offset < 0) {
                offset = 0;
            }
            $(musicbox).delay(500).animate({top: offset + "px"}, {duration: 700, queue: false});
        });
    }
});
{/literal}</script>
<style type="text/css">{literal}
#musicbox {
    position: relative;
    background: #fff;
    z-index: 100;
    padding: 12px 0 24px 0;
    border-bottom: 1px solid #cbd0d6;
}
{/literal}</style>
</head>

<body>
{assign var=url value="http://{$HOST}/articles/{$Article.filename}.html"}
{assign var=title value="{$Article.title}"}
{include file='includes/global_top.tpl' Share="true"}

<div class="global-content">

    <div class="C-left">

        <h1 class="H-article">{if $LANG.id == 'ru' && $Article.title_ru}{$Article.title_ru}{else}{$Article.title}{/if}</h1>
        <div class="H-genres">{$Article.date|date_format:"%B %e, %Y"} |
            {foreach from=$Article.genres item=row name=Genres}
                <a href="/articles/?genre={$row.filename}">{$row.name}</a>{if !$smarty.foreach.Genres.last}, {/if}
            {/foreach}
        </div>

        <div class="C-wrap">
            {if $LANG.id == 'ru'}
                {if $Article.content_ru}
                    {content}{$Article.content_ru}{/content}
                {else}
                    <div class="F-message" style="margin-bottom: 20px;">Чтобы автоматически перевести текст на русский язык, кликните по нему мышкой.</div>
                    <div id="sourceText">{content}{$Article.content}{/content}</div>
                    <div id="translation"></div>
                    <style>{literal}#sourceText p {cursor: default} #sourceText p:hover {background-color: #eee;}{/literal}</style>
                    <script>{literal}
                        var p;
                        function translateText(response) {
                            p.attr('lang', 'ru');
                            p.attr('en', p.html());
                            p.attr('ru', response.data.translations[0].translatedText);
                            p.html(response.data.translations[0].translatedText);
                        }
                        $(document).ready(function() {
                            $("#sourceText p").click(function() {
                                p = $(this);
                                if (p.attr('lang') == 'ru') {
                                    p.html(p.attr('en'));
                                    p.attr('lang', 'en');
                                } else {
                                    if (p.attr('ru')) {
                                        p.html(p.attr('ru'));
                                        p.attr('lang', 'ru');
                                    } else {
                                        var newScript = document.createElement('script');
                                        newScript.type = 'text/javascript';
                                        var sourceText = escape(p.html());
                                        newScript.src = 'https://www.googleapis.com/language/translate/v2?key=AIzaSyAB9LrEkUYPB7Ye-gPHoQTMqhCVJTQ-QqY&source=en&target=ru&callback=translateText&q=' + sourceText;
                                        document.getElementsByTagName('head')[0].appendChild(newScript);
                                    }
                                }
                            });
                        });
                    {/literal}</script>
                {/if}
            {else}
                {content}{$Article.content}{/content}
            {/if}
        </div>

        {*<div id="comments">
            <h2>{$LANG.comment.title}</h2>
            {include file='includes/comments.tpl' CommentCategory="{$smarty.const.COMMENT_CATEGORY_ARTICLE}" CommentParentId="{$Article.article_id}"}
        </div>*}

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
                <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                <fb:like href="http://{$HOST}/articles/{$Article.filename}.html" layout="button_count" font=""></fb:like>
            </div>
            <div class="social-button-container">
                <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                <script>{literal}!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');{/literal}</script>
            </div>
        </div>

        {if !empty($GeoTag)}
            <div style="border-bottom: 1px solid #cbd0d6; margin-bottom: 12px;">
                <div id="Map" class="Map" lat="{$GeoTag.lat}" lng="{$GeoTag.lng}" zoom="{$GeoTag.zoom}"></div>
                <div class="Wiki"><a href="{$GeoTag.wiki}" target="_blank">{$GeoTag.longname}</a></div>
                <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                <script type="text/javascript">{literal}
                    var map = $('#Map');
                    var options = {
                        zoom: parseInt(map.attr('zoom'), 10),
                        center: new google.maps.LatLng(map.attr('lat'), map.attr('lng')),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    new google.maps.Map(document.getElementById('Map'), options);
                {/literal}</script>
            </div>
        {/if}

        {if !empty($Audios)}
            <div id="musicbox">
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
        {/if}

        {if !empty($Artists)}
            <div>
                <h2>{$LANG.headers.relatedArtists}</h2>

                {foreach from=$Artists item=row}
                <div class="AR-wrap">
                    <div class="AR-photo"><a href="/artists/{$row.filename}.html"><span></span><img src="{$row|artist_picture:"s"}" alt="" width="50" height="50"/></a></div>

                    <div class="AR-name"><a href="/artists/{$row.filename}.html">{$row.name|escape}</a></div>
                    <div class="AR-genres">
                        {foreach from=$row.genres item=irow name=iGenres}
                            <a href="/articles/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
                        {/foreach}
                    </div>
                    
                    <div class="clear"></div>
                </div>
                {/foreach}
            </div>
        {/if}

    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
