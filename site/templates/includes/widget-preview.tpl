<div class="preview-wrap"><div class="preview-inner">
    <h3><a href="/articles/?genre={$FilterGenre}">{$FilterName}</a></h3>
    <div class="preview-title"><a href="/articles/{$Articles[$FilterGenre][0].filename}.html"><div><img src="{if $Articles[$FilterGenre][0].image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$Articles[$FilterGenre][0].image}.jpg{/if}" alt="" width="210" height="130" /></div><span>{$Articles[$FilterGenre][0].title}</span></a></div>
    <div class="preview-content" style="height: 100px; overflow: hidden;">{if $LANG.id == 'ru' && $Articles[$FilterGenre][0].description_ru}{$Articles[$FilterGenre][0].description_ru}{else}{$Articles[$FilterGenre][0].description}{/if}</div>
    {foreach from=$Articles[$FilterGenre] item=row name=articles}
        {if $smarty.foreach.articles.iteration > 1}
            <div>
                <div class="preview-title" style="padding-bottom: 0;"><a href="/articles/{$row.filename}.html" style="height: 50px;overflow: hidden;"><div style="width: 50px;height: 50px;overflow: hidden;padding: 0;margin-right: 10px;float: left;"><img src="{if $row.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$row.image}.jpg{/if}" alt="" width="81" height="50" /></div><span style="font-size: 11px;line-height: normal;">{if $LANG.id == 'ru' && $row.title_ru}{$row.title_ru}{else}{$row.title}{/if}</span></a></div>
                <div class="clear"></div>
            </div>
        {/if}
    {/foreach}
</div></div>
