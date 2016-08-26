{foreach from=$Articles item=row}
    <li>
        <a href="/m/a/{$row.filename}.html">
            <img src="{if $row.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$row.image}.jpg{/if}" alt="" class="A-preview"/>
            <span class="A-link">{$row.title}</span>
        </a>
    </li>
{/foreach}
{if $NextArticlesPage > 0}
<li><a href="/m/a{$NextArticlesPage}" target="_replace">Get more articles</a></li>
{/if}
