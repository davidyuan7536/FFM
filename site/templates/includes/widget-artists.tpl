<div class="artists-column">
    <div class="artists-region"><h3><a href="/artists/?region={$ColumnCode}">{$ColumnTitle}</a></h3></div>

    {foreach from=$Artists[$ColumnCode] item=row}
    <div class="AR-wrap">
        <div class="AR-photo"><a href="/artists/{$row.filename}.html"><span></span><img src="{$row|artist_picture:"s"}" alt="" width="50" height="50"/></a>
        {if !empty($row.audio)}
            <div class="Artist-Mini" title="{$row.audio.audio_name|escape}" url="{$smarty.const.__FFM_AUDIO_FRONT__}{$row.audio.audio_filename|escape:url}">
                <div class="Artist-Mini-Image"></div>
            </div>
        {/if}
        </div>

        <div class="AR-name"><a href="/artists/{$row.filename}.html">{$row.name|escape}</a></div>
        <div class="AR-genres">
            {foreach from=$row.genres item=irow name=iGenres}
                <a href="/artists/?genre={$irow.filename}">{$irow.name}</a>{if !$smarty.foreach.iGenres.last}, {/if}
            {/foreach}
        </div>
    </div>
    {/foreach}
</div>
