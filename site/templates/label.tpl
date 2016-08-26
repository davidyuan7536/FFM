<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing,places"></script>
    <script type="text/javascript" src="/js/maps.js?{$V}"></script>
</head>

<body>
{include file='includes/global_top.tpl'}
<div class="global-content">
    <div class="Label-header">
        <img src="/i/decor/ffm-music-label.png" />
        <h1>{$LANG.headers.label}</h1>
        <div class="Label-artists-header collapsed">
            <img src="data:image/png;base64,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" />
            {$LANG.label.labelArtists|upper}
        </div>
    </div>
    <input type="hidden" id="PlaceLat" value="{$LastAddedReleaseNMPlace.place_lat}">
    <input type="hidden" id="PlaceLng" value="{$LastAddedReleaseNMPlace.place_lng}">
    <input type="hidden" id="PlaceUrl" value="http://{$smarty.const.__NM_HOST__}/places/{$LastAddedReleaseNMPlace.place_uuid}">
    <div id="Label-map"></div>

    <div class="Label-container">
        <div class="A-row A-row-first">
        {section name=row loop=$releases}
            <div class="Label-release">
                <div class="Label-release-header">
                    <div class="Label-release-title">
                        <a href="/label/{$releases[row].filename}.html">
                            {if $LANG.id == 'ru' && $releases[row].title_ru}{$releases[row].title_ru}{else}{$releases[row].title}{/if}
                        </a>
                    </div>
                    <div class="Label-release-row">
                        <div class="Label-release-artist">
                            {if is_array($releases[row].artist)}
                                <a href="/artists/{$releases[row].artist.filename}.html">{$releases[row].artist.name}</a>
                            {else}
                                {$LANG.label.variousArtists}
                            {/if}
                        </div>
                        {if $releases[row].ffm_id}
                            <a class="Label-release-id" href="/label/{$releases[row].filename}.html">{strtoupper($releases[row].ffm_id)}</a>
                        {/if}
                    </div>
                </div>
                <div class="Label-release-player">
                    {$releases[row].player_for_list}
                </div>
                <div class="Label-release-description">
                    {if $LANG.id == 'ru' && $releases[row].description_ru}{strip_tags($releases[row].description_ru, '<a>')}{else}{strip_tags($releases[row].description, '<a>')}{/if}
                </div>
                <div class="Label-release-genres">
                    {assign var=genres value=$releases[row].genres}
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
    <div class="P-wrap">
        {include file='includes/widget-pages-links.tpl'}
    </div>
</div>
{include file='includes/global_bottom.tpl'}

<div class="Label-artists">
    {assign var="artistsList" value=$labelArtists}
    {include file='artist_list.tpl'}
</div>
<script>
jQuery(document).ready(function($) {
    var isDropdownOpen = false;
    $('.Label-artists-header').click(function() {
        var $this = $(this);
        if (! isDropdownOpen) {
            var offset = $this.offset();
            $('.Label-artists')
                    .css({
                        'top': offset.top + $this.outerHeight() - 3,
                        'left': offset.left,
                        'display': 'block'
                    });

            $this.addClass('expanded');
            $this.removeClass('collapsed');
            isDropdownOpen = true;
        } else {
            $('.Label-artists').hide();
            $this.removeClass('expanded');
            $this.addClass('collapsed');
            isDropdownOpen = false;
        }
    });

    var lat = $('#PlaceLat').val();
    var lng = $('#PlaceLng').val();
    var nmPlaceUrl = $('#PlaceUrl').val();
    var Maps = maps();
    var map = Maps.init($('#Label-map'), {
        zoom: 15,
        center: new google.maps.LatLng(lat, lng),
        mapTypeControl: true,
        readonly: true,
        scrollwheel: true,
        streetViewControl: true
    });
    map.setMarker(lat, lng, nmPlaceUrl);
});
</script>
</body>
</html>