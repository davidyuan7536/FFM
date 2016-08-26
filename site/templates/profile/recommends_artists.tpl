{foreach from=$Recommends item=row}
    <div class="AR-wrap">
        <div class="AR-photo"><a href="/artists/{$row.filename}.html"><span></span><img src="{$row|artist_picture:"s"}" alt="" width="50" height="50"/></a>
        {if !empty($row.track) && !empty($row.track.track_filename)}
            <div class="Artist-Mini" title="{$row.track.track_name|escape}" url="{$smarty.const.__FFM_ARCHIVE_FRONT__}{$row.track.release_hash}/{$row.track.track_filename|escape:url}">
                <div class="Artist-Mini-Image"></div>
            </div>
        {/if}
        </div>

        <div class="AR-name"><a href="/artists/{$row.filename}.html">{$row.name|escape}</a> {if $Editable}<button class="F-button-mini RecommendsArtistsDelete" artist="{$row.artist_id}">{$LANG.link.delete}</button>{/if}</div>
        <div class="AR-genres">
            {foreach from=$row.genres item=irow name=iGenres}
                <a href="/artists/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
            {/foreach}
        </div>

        <div class="clear"></div>
    </div>
{/foreach}
