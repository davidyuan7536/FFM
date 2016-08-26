{if empty($Articles)}
    <div class="AA-empty">{$LANG.artist.articlesEmpty}</div>
{/if}
{foreach from=$Articles item=row name=articles}
    <div class="AA-wrap" style="float: {if $smarty.foreach.articles.iteration % 2 == 0}right{else}left{/if};">
        <div class="AA-image"><a href="/articles/{$row.filename}.html"><img src="{if $row.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$row.image}.jpg{/if}" alt="" width="130" height="80"/></a></div>
        <div class="AA-content">
            <div class="AA-title"><a href="/articles/{$row.filename}.html">{if $LANG.id == 'ru' && $row.title_ru}{$row.title_ru}{else}{$row.title}{/if}</a></div>
            <div class="AA-text">{if $LANG.id == 'ru' && $row.description_ru}{$row.description_ru}{else}{$row.description}{/if}</div>
        </div>
        <div class="clear"></div>
    </div>
    {if !($smarty.foreach.articles.iteration % 2)}<div class="clear"></div>{/if}
{/foreach}
<div class="clear"></div>
