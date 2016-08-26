<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
<style type="text/css">{literal}
html, body {
margin: 0;
padding: 0;
background-color: #fff;
color: #000;
font: 12px Helvetica, Arial, sans-serif;
cursor: default;
}
form, td {
margin: 0;
padding: 0;
}
table {
border-collapse: collapse;
table-layout: fixed;
}
a img { border: none; }
@-webkit-keyframes zoomer {
0% { -webkit-transform: scale(1) }
50% { -webkit-transform: scale(1.2) }
100% { -webkit-transform: scale(1) }
}
#Play:hover { 
-webkit-animation-name: zoomer;
-webkit-animation-duration: 200ms;
-webkit-animation-iteration-count: 1;
-webkit-animation-timing-function: ease-out;

-moz-transform: scale(1.2);
-moz-transition-duration: 200ms;
-moz-transition-timing-function: ease-out;

-o-transform: scale(1.2);
-o-transition-duration: 200ms;
-o-transition-timing-function: ease-out;

-ms-transform: scale(1.2);
-ms-transform-duration: 200ms;
-ms-transform-timing-function: ease-out;
}
#Play:active {
-webkit-transform:scale(0.8)
}
.radius {
-webkit-border-radius: 8px;
-moz-border-radius: 8px;
border-radius: 8px;
overflow: hidden;
}
{/literal}</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<script src="/js/media.js"></script>
</head>

<body>
<input type="hidden" id="url" value="http://{$HOST}{$smarty.const.__FFM_ARCHIVE_FRONT__}{$Release.release_hash}/{$Track.track_filename|escape:'url'}">
<input type="hidden" id="Duration" value="{$Track.track_length}">
<table style="width: 100%;">
<tr style="vertical-align: top;">
    <td style="width: 150px">
        <div style="position: relative;">
            <img id="Play" src="/i/player/button-play.png" width="50" height="51" style="position: absolute; top: 40px; left: 40px; z-index:100; cursor: pointer;">
            <img src="{if $Release.release_image == 0}http://{$HOST}/i/decor/placeholder-release_m.png{else}http://{$HOST}{$smarty.const.__FFM_ARCHIVE_FRONT__}{$Release.release_hash}/cover_m.jpg?{$Release.release_image}{/if}" width="130" height="130">
        </div>
    </td>
    <td>
        <div id="SharePanel" class="radius" style="display: none;height: 128px;border: 1px solid #e3e8ee; font-weight: bold; color: #6a6d71;">
            <img src="/i/player/button-close.png" width="17" height="17" style="float: right;cursor: pointer; padding: 2px;" id="ShareClose" />

            {assign var=url value="http://{$HOST}/media/t/{$Track.track_id}/"}
            <div style="padding: 6px;">
                <label style="width: 120px; display: inline-block; vertical-align: 4px;">{$LANG.link.shareTrack}: </label>
                <a class="share" title="{$LANG.link.facebook}" href="http://www.facebook.com/sharer.php?u={$url}"><img src="/i/icons/share_f.png" alt="" width="20" height="20" /></a>
                <a class="share" title="{$LANG.link.vkontakte}" href="http://vkontakte.ru/share.php?url={$url}"><img src="/i/icons/share_v.png" alt="" width="20" height="20" /></a>
                <a class="share" title="{$LANG.link.twitter}" href="http://twitter.com/share?url={$url}"><img src="/i/icons/share_t.png" alt="" width="20" height="20" /></a>
            </div>

            <div style="padding: 6px;">
                <label for="Link" style="width: 120px; display: inline-block;">{$LANG.link.getLink}: </label>
                <input type="text" id="Link" value="http://{$HOST}/media/t/{$Track.track_id}/" readonly="readonly" style="width: 200px;"/>
            </div>

            <div style="padding: 6px;">
                <label for="Code" style="width: 120px; display: inline-block;">{$LANG.link.embedCode}: </label>
                <input type="text" id="Code" value='<iframe src="http://{$HOST}/media/t/{$Track.track_id}/embed" frameborder="0" scrolling="no" width="645" height="130" marginwidth="0" marginheight="0"></iframe>' readonly="readonly" style="width: 200px;"/>
            </div>

        </div>
        <div id="PlayerPanel">
            <div style="font-size: 20px; line-height: 1em; white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">{$Track.track_name|escape}</div>
            <div id="Genres" style="border-bottom: 1px solid #000;height: 26px;font-size: 10px;color: #aaafb6;overflow: hidden;"><div style="padding-top: 4px;">
                {foreach from=$Track.genres item=row name=genres}
                    <a href="/artists/?genre={$row.filename}" target="_blank" style="color: #aaafb6;">{$row.name}</a>{if !$smarty.foreach.genres.last}, {/if}
                {/foreach}
            </div></div>
            <table style="width: 100%;">
            <tr style="vertical-align: top;">
                <td>
                    <div style="height: 28px;font-size: 11px;font-weight: bold;">
                        <div id="TimeCurrent" style="float: left;padding-top: 6px;">0:00</div>
                        <div id="TimeLeft" style="float: right;padding-top: 6px;">{$Track.track_length|time_format}</div>
                    </div>
                    <div style="position: relative;">
                        <div id="Container" class="radius" style="background-color: #cbd0d6; height: 15px;">
                            <div class="radius"><div id="Loaded" style="background-color: #98a1a4; height: 15px; width: 0;"></div></div>
                            <div class="radius" style="margin-top: -15px;"><div id="Progress" style="background-color: #000000; height: 15px; width: 0;"></div></div>
                        </div>
                    </div>
                </td>
                <td style="width: 10px;"></td>
                <td style="width: 70px;">
                    <div style='height: 28px;background: url("/i/player/volume.png") 15px 6px no-repeat;'></div>
                    <div id="Volume" class="radius" style="background-color: #cbd0d6; height: 15px;">
                        <div class="radius"><div id="VolumeSize" style="background-color: #98a1a4; height: 15px; width: 0;"></div></div>
                    </div>
                </td>
            </tr>
            </table>
            <div style="padding-top: 10px;">
                <table style="width: 100%;">
                <tr style="vertical-align: top;">
                    <td></td>
                    <td style="width: 10px;"></td>
                    <td style="width: 35px;"><img src="/i/player/button-share.png" width="30" height="29" id="Share" style="cursor: pointer;" /></td>
                    <td style="width: 35px;">{if $Track.track_share}<a href="http://{$HOST}{$smarty.const.__FFM_ARCHIVE_FRONT__}{$Release.release_hash}/{$Track.track_filename|escape:'url'}"><img src="/i/player/button-download.png" width="30" height="29" /></a>{/if}</td>
                </tr>
                </table>
            </div>
        </div>
    </td>
</tr>
</table>
</body>
</html>
