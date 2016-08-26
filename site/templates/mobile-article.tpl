<div id="{$Article.filename}" class="page">
    <h1 class="H-article">{$Article.title}</h1>
    <div class="H-genres">{$AudioLink}{$Article.date|date_format:"%B %e, %Y"} |
        {foreach from=$Article.genres item=row name=Genres}
            {$row.name}{if !$smarty.foreach.Genres.last}, {/if}
        {/foreach}
    </div>

    <div class="C-wrap">
        {content}{$Article.content}{/content}
    </div>
</div>
