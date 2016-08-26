<span class="H-genres" id="ProfileGenres">
    {foreach from=$Promoter.genres item=irow name=iGenres}
        <a href="/promoters/?genre={$irow.filename}">{$irow.name|escape}</a>{if !$smarty.foreach.iGenres.last}, {/if}
    {/foreach}
</span>
