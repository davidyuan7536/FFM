{if empty($Releases)}
    <div class="Release-empty">{$LANG.artist.releasesEmpty}</div>
{/if}
    <div class="Release-item Release-item-first"></div>
{foreach from=$Releases item=row name=releases}
    <div id="{$row.release_hash}" class="Release-item{if $smarty.foreach.releases.iteration % 2 == 0} Release-right{else} Release-left{/if}">
        <div class="Release-inner">
            <div class="Release-preview">
                <div class="Release-select" target="{$row.release_hash}"><strong>{$row.release_name|escape}</strong><span> ({$row.release_year|escape})</span></div>
                <div class="Release-image" target="{$row.release_hash}" style="float: left;">
                    {if $row.release_image == 0}
                        <div><img src="/i/decor/placeholder-release_s.png" alt="" width="50" height="50"/></div>
                    {else}
                        <div><img src="{$smarty.const.__FFM_ARCHIVE_FRONT__}{$row.release_hash}/cover_s.jpg?{$row.release_image}" alt="" width="50" height="50"/></div>
                    {/if}
                </div>
                <div class="Release-tracklist" style="margin-left: 60px;">
                    {foreach from=$row.tracks item=track name=tracklist}
                        <div style="border-bottom: 1px solid #000; padding: 2px;{if $smarty.foreach.tracklist.iteration > 3}display: none;{/if}"
                            {if !empty($track.track_filename)}
                                class="Hello-Mini" title="{$track.track_name|escape}" url="{$smarty.const.__FFM_ARCHIVE_FRONT__}{$track.release_hash}/{$track.track_filename|escape:url}"
                            {/if}>{if !empty($track.track_filename)}<span class="Hello-Mini-Image"></span>{/if}
                            {$smarty.foreach.tracklist.iteration|string_format:"%02s"}: {$track.track_name|escape} {if !empty($track.track_length)}({$track.track_length|time_format}){/if}</div>
                        {if $smarty.foreach.tracklist.iteration == 3 && count($row.tracks) > 3}
                            <span onclick="$(this).hide().nextAll('div').slideDown()" style="text-decoration: underline;cursor: pointer;font-size: 11px;">view full info</span>                        
                        {/if}
                    {/foreach}
                </div>
                <br clear="all"/>
            </div>
            <div class="Release-view">
                <div class="Release-image" target="{$row.release_hash}">
                    {if $row.release_image == 0}
                        <div><img src="/i/decor/placeholder-release_s.png" alt="" width="50" height="50"/></div>
                    {else}
                        <div><img src="{$smarty.const.__FFM_ARCHIVE_FRONT__}{$row.release_hash}/cover_s.jpg?{$row.release_image}" alt="" width="50" height="50"/></div>
                    {/if}
                </div>
                <div class="Release-info">
                    <div class="Release-select" target="{$row.release_hash}"><strong>{$row.release_name|escape}</strong></div>
                    <div class="Release-text">
                        <div><strong>{$LANG.release.year}: </strong><span>{$row.release_year|escape}</span></div>
                        <div><strong>{$LANG.release.label}: </strong><span>{$row.release_label|escape}</span></div>
                        {if !empty($row.genres)}
                            <div><strong>{$LANG.release.genres}: </strong>
                                <span class="Release-genre">{foreach from=$row.genres item=genre name=genres}
                                    <span>{$genre.name}</span>{if !$smarty.foreach.genres.last}, {/if}
                                {/foreach}</span>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
            {if $Editable}
                <div class="Release-buttons">
                    <button class="F-button-mini A-Releases-row-edit" value="{$row.release_id}">{$LANG.link.editMini}</button>
                    <button class="F-button-mini A-Releases-row-delete" value="{$row.release_id}">{$LANG.link.delete}</button>
                </div>
            {/if}
        </div>
    </div>
    {if $smarty.foreach.releases.iteration % 2 == 0}<div class="clear"></div>{/if}
{/foreach}
{if !empty($Releases)}
<br clear="all"/>
<div class="Release-marker"><img src="/i/icons/release-marker.png" width="12" height="7" alt=""/></div>
<script type="text/javascript">{literal}initReleaseListActions();{/literal}</script>
{if $Editable}
<script type="text/javascript">{literal}initReleaseList();{/literal}</script>
{/if}
{/if}
