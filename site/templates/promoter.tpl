<!DOCTYPE html>
<html lang="{$LANG.id}" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<meta property="og:title" content="{$Promoter.promoter_name|escape}" />
<meta property="og:type" content="musician" />
<meta property="og:url" content="http://{$HOST}/promoters/{$Promoter.promoter_filename}.html" />
<meta property="og:image" content="http://{$HOST}{$Promoter|promoter_picture:"b"}" />
<meta property="og:site_name" content="{$LANG.global.title}" />
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing,places"></script>
<script type="text/javascript" src="/js/maps.js?{$V}"></script>
</head>

<body>
{assign var=url value="http://{$HOST}/promoters/{$Promoter.promoter_filename}.html"}
{assign var=title value="{$Promoter.promoter_name|escape}"}
{include file='includes/global_top.tpl' Share="true"}

<div class="global-content">

    <div class="C-left">

        <h1 class="H-promoter">{include file='promoter/title.tpl'}
            {if $Editable}
                <span id="ProfileTitleEdit" style="display: none;">
                    <label style="font-size: 10pt;">{$LANG.promoter.labelName}: <input id="ProfileName" type="text" class="F-input" style="width: 120px;" placeholder="{$LANG.promoter.name}"/></label>
                    <label style="font-size: 10pt;">{$LANG.promoter.labelNameRu}: <input id="ProfileNameRu" type="text" class="F-input" style="width: 120px;" placeholder="{$LANG.promoter.nameRu}"/></label>
                    <label style="font-size: 10pt;"><input id="ProfileClub" type="checkbox" /> Club</label>
                </span>
                <button class="F-button" id="ButtonEdit">{$LANG.link.edit}</button>
                <button class="F-button" id="ButtonSave" style="display: none;">{$LANG.link.save}</button>
                <button class="F-button" id="ButtonCancel" style="display: none;">{$LANG.link.cancel}</button>
            {/if}
        </h1>
        <div class="A-tags rel">
            {if $Country|@count > 0}<h3><a href="/promoters/?region={$Country.filename}">{$Country.filename|upper}</a></h3>{/if}
            {include file='promoter/genres.tpl'}
            {if $Editable}
                <span id="ProfileGeoEdit" style="display: none;">
                    <input type="hidden" id="GeoSearchId"/>
                    <input type="text" class="F-input" style="width: 75px;" id="GeoSearch" placeholder="{$LANG.promoter.geoText}" />
                    <script type="text/javascript">{literal}
var accentMap = {
    "a": "а",
    "b": "б",
    "v": "в",
    "g": "г",
    "d": "д",
    "e": "е",
    "z": "з",
    "i": "и",
    "k": "к",
    "l": "л",
    "m": "м",
    "n": "н",
    "o": "о",
    "p": "п",
    "r": "р",
    "s": "с",
    "t": "т",
    "u": "у",
    "f": "ф",
    "h": "х",
    "y": "я"
};
var availableTags = [
{/literal}
{foreach from=$GeoTagList item=row name=geotaglist}
{ldelim}value:"{$row.geo_tag_id}",label:"{$row.name}"{rdelim}{if !$smarty.foreach.geotaglist.last}, {/if}
{/foreach}
{literal}
];
var normalize = function( term ) {
    var ret = "";
    for ( var i = 0; i < term.length; i++ ) {
        ret += accentMap[term.charAt(i).toLowerCase()] || term.charAt(i);
    }
    return ret;
};
$('#GeoSearch').autocomplete({
    source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( availableTags, function( value ) {
            value = value.label || value.value || value;
            return matcher.test( value ) || matcher.test( normalize( value ) );
        }) );
    },
    focus: function( event, ui ) {
        $( '#GeoSearch' ).val( ui.item.label );
        return false;
    },
    select: function( event, ui ) {
        $( '#GeoSearch' ).val( ui.item.label );
        $( '#GeoSearchId' ).val( ui.item.value );
        return false;
    }
}).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
        .data('item.autocomplete', item)
        .append('<a>' + item.label + '</a>')
        .appendTo(ul);
};
                    {/literal}</script>
                </span>
                <span id="ProfileGenresEdit" style="display: none;">
                    <input type="text" class="F-input" style="width: 100px;" id="GSearch" placeholder="{$LANG.promoter.genres}" />
                    <span id="ProfileGList"></span>
                </span>
            {/if}
        </div>

        <div class="A-card-wrap">
            {include file='promoter/card.tpl'}
            {if $Editable}
                <div id="ProfileCardEdit" style="display: none;">
                    <div class="A-card-image"><span style="position: absolute; display: block; left: 0; top: 0; width: 130px; height: 130px;"><span id="ProfileImageUploaderHolder"></span></span><img src="{$Promoter|promoter_picture}" id="ProfileImage" alt="" width="130" height="130"/><div id="ProfileImageUploaderStatus"></div></div>
                    <div class="A-card-info">
                        <div>
                            <textarea id="ProfileDescription" maxlength="1000" class="F-text" style="width: 400px; height: 100px;" placeholder="{$LANG.promoter.description}"></textarea>
                        </div>
                        <div>
                            <textarea id="ProfileLinks" maxlength="500" class="F-text" style="width: 400px; height: 75px;" placeholder="{$LANG.promoter.links}"></textarea>
                        </div>
                        <div>
                            <textarea id="ProfileExtra" maxlength="1000" class="F-text Profile-extra-info" style="width: 400px; height: 50px;" placeholder="{$LANG.promoter.extra}"></textarea>
                        </div>
                    </div>
                </div>
            {/if}
            <div class="clear"></div>
        </div>

        <div id="tabs" style="min-height: 200px;">
            <ul>
                <li><a href="?tab=articles">{$LANG.promoter.tabArticles}</a></li>
                <li><a href="?tab=videos"><span>{$LANG.promoter.tabVideos}</span></a></li>
                <li><a href="?tab=photos"><span>{$LANG.promoter.tabPhotos}</span></a></li>
            </ul>
        </div>

        <script type="text/javascript">{literal}
            $("#tabs").tabs(PROFILE_OPTIONS);
        {/literal}</script>
    </div>

    <div class="C-right">
        <div style="margin-bottom: 10px;">
            <fb:like href="http://{$HOST}/promoters/{$Promoter.promoter_filename}.html" show_faces="true" width="285" font=""></fb:like>
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

        <div style="margin: 10px 0; padding-bottom: 10px; border-bottom: 1px solid #cbd0d6;">
            {if !empty($Artists)}
                <h2>{$LANG.headers.promotersArtists}</h2>

                <div id="PromotersArtists">{include file='promoter/promoters_artists.tpl'}</div>
            {else}
                {if $Editable}
                    <h2>{$LANG.headers.promotersArtists}</h2>
                    
                    <div id="PromotersArtists"></div>
                {/if}
            {/if}
            {if $Editable}
                <input type="hidden" value="{$LANG.promoter.artistDeleteConfirmation}" id="PromoterArtistsConfirm">
                <div style="padding-top: 10px;"><input id="PromoterArtistsInput" placeholder="{$LANG.link.addPromotersArtist}" /></div>
<style type="text/css">{literal}
.ui-autocomplete-loading { background: white url('/i/lightbox/lightbox-ico-loading.gif') right center no-repeat; }
{/literal}</style>
<script type="text/javascript">{literal}
$(function() {
    $('#PromoterArtistsInput').autocomplete({
        source: self.location.pathname,
        minLength: 2,
        select: function(event, ui) {
            if (ui.item) {
                var data = {
                    'Action': 'AddPromotersArtist',
                    'Id': ui.item.artist_id
                };
                $.post(self.location.pathname, data, onPromoterArtistsLoad, 'json');
            }
        }
    }).data('autocomplete')._renderItem = function(ul, item) {
        return $("<li></li>")
            .data('item.autocomplete', item)
            .append('<a>' + item.name + '</a>')
            .appendTo(ul);
    };
    $('.PromotersArtistsDelete').live('click', function() {
        if (confirm($('#PromoterArtistsConfirm').val())) {
            var data = {
                'Action': 'DeletePromotersArtist',
                'Id': $(this).attr('artist')
            };
            $.post(self.location.pathname, data, onPromoterArtistsLoad, 'json');
        }
    });
    function onPromoterArtistsLoad(result) {
        if (result['message']) {
            alert(result['message'])
        } else {
            $.each(result['elements'], function(key, value) {
                $(key).empty().html(value);
            });
        }
    }
});
{/literal}</script>
            {/if}
        </div>
    </div>
    
    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
