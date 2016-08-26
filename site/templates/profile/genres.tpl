<span class="H-genres" id="ProfileGenres">
    {foreach from=$Artist.genres item=irow name=iGenres}
        <a href="/artists/?genre={$irow.filename}">{$irow.name|escape}</a>{if !$smarty.foreach.iGenres.last}, {/if}
    {/foreach}
</span>
