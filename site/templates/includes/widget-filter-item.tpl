<div class="filter-extra-block">
    <div class="filter-extra-title"><a href="?genre={$FilterGenre|escape:'url'}">{$Genres[$FilterGenre].name}</a></div>
    {if !empty($Genres[$FilterGenre].childNodes)}
    <ul>
        {foreach from=$Genres[$FilterGenre].childNodes item=item}
            <li><a href="?genre={$item.filename|escape:'url'}">{$item.name}</a></li>
        {/foreach}
    </ul>
    {/if}
</div>
