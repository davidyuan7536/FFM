<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl' noindex='true'}
<script type="text/javascript" src="/js/filter.js"></script>
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    {if $Filtered}
        <h1 class="H-filter">{$Title}</h1>
    {else}
        <h1>{$Title}</h1>
    {/if}

    <div class="C-left">
        {include file='includes/widget-filter.tpl'}    

        {foreach from=$Articles item=row name=Articles}
            <div class="article-preview-wrap{if $smarty.foreach.Articles.first} article-preview-first{/if}">
                <div class="article-preview-image"><a href="{$row.filename}.html"><img src="{if $row.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$row.image}.jpg{/if}" alt="" width="210" height="130"/></a></div>
                <div class="article-preview-title"><a href="{$row.filename}.html">{if $LANG.id == 'ru' && $row.title_ru}{$row.title_ru}{else}{$row.title}{/if}</a></div>
                <div class="article-preview-content">{if $LANG.id == 'ru' && $row.description_ru}{$row.description_ru}{else}{$row.description}{/if}</div>
                <div class="clear"></div>
            </div>
        {/foreach}

        <div class="P-wrap">
            {include file='includes/widget-pages-links.tpl'}    
        </div>

    </div>

    <div class="C-right">
        {if !empty($Region)}
            <div style="border-bottom: 1px solid #cbd0d6; margin-bottom: 12px;">
                <div id="Map" class="Map" lat="{$Region.lat}" lng="{$Region.lng}" zoom="{$Region.zoom}"></div>
                <div class="Wiki"><a href="{$Region.wiki}" target="_blank">{$Region.longname}</a></div>
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

        {if !empty($Artists)}
            <div>
                <h2>{$LANG.headers.relatedArtists}</h2>

                {foreach from=$Artists item=row}
                <div class="AR-wrap">
                    <div class="AR-photo"><a href="/artists/{$row.filename}.html"><span></span><img src="{$row|artist_picture:"s"}" alt="" width="50" height="50"/></a></div>

                    <div class="AR-name"><a href="/artists/{$row.filename}.html">{$row.name|escape}</a></div>
                    <div class="AR-genres">
                        {foreach from=$row.genres item=irow name=iGenres}
                            <a href="/artists/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
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
