{foreach from=$Pages item=page name=Pages}
    {if $page.link}
        <a href="{$page.link}" {if $page.arrow}class="P-arrow"{/if}>{$page.text}</a>
    {else}
        <strong>{$page.text}</strong>
    {/if}
    {if !$smarty.foreach.Pages.last} | {/if}
{/foreach}
