<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl' noindex='true'}
<script type="text/javascript" src="/js/filter.js"></script>
<script type="text/javascript">{literal}
$(function() {
    $('.Artist-Maximus').miniPlayer('Artist-Maximus');
});
{/literal}
</script>
</head>

<body>
{include file='includes/global_top.tpl' tooltip='true'}

<div class="global-content">

    <div class="C-left">
        {include file='includes/widget-filter.tpl'}
    </div>
    <div class="clear"></div>

    {if $Filtered}
        <h1 class="H-filter">{$Title}</h1>
    {else}
        <h1>{$Title}</h1>
    {/if}

    <div class="A-container-wrap"><div class="A-container-inner">

        <div class="A-row A-row-first">
        {section name=row loop=$Artists}
            <div class="A-wrap">
                <div class="A-image"><a href="{$Artists[row].filename}.html"><span class="A-frame"></span><img src="{$Artists[row]|artist_picture}" alt="" width="130" height="130"/></a>
                {if !empty($Artists[row].audio)}
                    <div class="Artist-Maximus" title="{$Artists[row].audio.audio_name|escape}" url="{$smarty.const.__FFM_AUDIO_FRONT__}{$Artists[row].audio.audio_filename|escape:url}">
                        <div class="Artist-Maximus-Image"></div>
                    </div>
                {/if}
                </div>
                <div class="A-info">
                    <div class="AR-name"><a href="{$Artists[row].filename}.html">{$Artists[row].name|escape}</a></div>
                    <div class="AR-genres">
                        {foreach from=$Artists[row].genres item=g name=Genres}
                            <a href="/artists/?genre={$g.filename}">{$g.name}</a>{if !$smarty.foreach.Genres.last}, {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
            {if $smarty.section.row.iteration % 3 == 0}
                </div><div class="A-row">
            {/if}
        {/section}
        </div>

        <div class="clear"></div>
    </div></div>

    <div class="P-wrap">
        {include file='includes/widget-pages-links.tpl'}
    </div>

</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>