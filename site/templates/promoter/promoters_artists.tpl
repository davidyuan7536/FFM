{foreach from=$Artists item=row}
    <div class="AR-wrap">
        <div class="AR-photo"><a href="/artists/{$row.filename}.html"><span></span><img src="{$row|artist_picture:"s"}" alt="" width="50" height="50"/></a></div>

        <div class="AR-name"><a href="/artists/{$row.filename}.html">{$row.name|escape}</a> {if $Editable}<button class="F-button-mini PromotersArtistsDelete" artist="{$row.artist_id}">{$LANG.link.delete}</button>{/if}</div>
        <div class="AR-genres">
            {foreach from=$row.genres item=irow name=iGenres}
                <a href="/artists/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
            {/foreach}
        </div>

        <div class="clear"></div>
    </div>
{/foreach}
