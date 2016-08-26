<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl' noindex='true'}
<script type="text/javascript" src="/js/filter.js"></script>
</head>

<body>
{include file='includes/global_top.tpl'}

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
        {section name=row loop=$Promoters}
            <div class="A-wrap">
                <div class="A-image">
                        {if $Promoters[row].promoter_status == $smarty.const.PROMOTER_STATUS_CLUB}<div class="AR-club"></div>{else}<div class="AR-person"></div>{/if}
                        <a href="{$Promoters[row].promoter_filename}.html"><img src="{$Promoters[row]|promoter_picture}" alt="" width="130" height="130"/></a></div>
                <div class="A-info">
                    <div class="AR-name"><a href="{$Promoters[row].promoter_filename}.html">{$Promoters[row].promoter_name|escape}</a></div>
                    <div class="AR-genres">
                        {foreach from=$Promoters[row].genres item=g name=Genres}
                            <a href="/promoters/?genre={$g.filename}">{$g.name}</a>{if !$smarty.foreach.Genres.last}, {/if}
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