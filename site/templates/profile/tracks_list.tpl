{if $Editable}
<input type="hidden" id="TrackReleaseHash" value="{$Release.release_hash}" />
<button class="F-button" style="float: right;" onclick="editTrack();">{$LANG.link.addTrack}</button>
<script type="text/javascript">{literal}initTrackList();{/literal}</script>
{/if}
<h1 style="font-size: 24px;font-weight: bold;text-transform: none;">{$Release.release_name|escape}</h1>
{if empty($Tracks)}
    <div class="Release-track-list-empty">{$LANG.artist.tracksEmpty}</div>
{else}
    <div id="PlayerFrame" class="Release-track-frame" style="display: none;"></div>
    <div class="Release-track-list">
        {foreach from=$Tracks item=row name=tracks}
            <div track="{$row.track_id}" class="Release-track-wrap{if !empty($row.track_filename)} Release-track-clickable{else} Release-track-unclickable{/if}">
                <table width="100%"><tr>
                    <td width="30"><b>{$smarty.foreach.tracks.iteration|string_format:"%02s"}</b></td>
                    <td>{$row.track_name|escape} {if !empty($row.track_length)}({$row.track_length|time_format}){/if}</td>
                    <td width="150" align="right">{if $Editable}
                        <div class="Track-buttons">
                            <button class="F-button-mini A-Track-edit" value="{$row.track_id}">{$LANG.link.editMini}</button>
                            <button class="F-button-mini A-Track-delete" value="{$row.track_id}">{$LANG.link.delete}</button>
                        </div>
                    {/if}</td>
                    <td width="30" align="center">{if !empty($row.track_filename)}<img src="/i/icons/play-m.png" width="8" height="10" alt="{$row.track_filename|escape}" />{/if}</td>
                    <td width="100" align="right"><div style="font-size: 10px;font-weight: bold;color: #6a6d71;">{if !empty($row.track_size)}{$row.track_size|bytes_format}{/if}</div></td>
                    <td width="30" align="right"><div style="height: 22px;">{if !empty($row.track_filename) && $row.track_share == 1}<a href="{$smarty.const.__FFM_ARCHIVE_FRONT__}{$row.release_hash}/{$row.track_filename|escape:url}"><img src="/i/icons/download.png" width="20" height="19" alt="{$row.track_filename|escape}" /></a>{/if}</div></td>
                </tr></table>
            </div>
        {/foreach}
    </div>
    {if !empty($Release.release_zip) && !empty($Downloadable)}
        <div class="Release-track-list-total">
            <span><a href="{$smarty.const.__FFM_ARCHIVE_FRONT__}{$Release.release_hash}/{$Release.release_hash}.zip">Download all as ZIP-file</a> </span><b>({$Release.release_zip|bytes_format})</b>
        </div>
    {/if}
    <script type="text/javascript">{literal}
        var current;

        function unSelect() {
            if (current) {
                current.removeClass('Release-track-selected');
            }
        }

        $('.Release-track-unclickable').click(function() {
            unSelect();
            $('#PlayerFrame').slideUp().empty();
        });
        
        $('.Release-track-clickable').click(function() {
            unSelect();
            var id = $(this).attr('track');
            $('<iframe frameborder="0" scrolling="no" width="645" height="130" marginwidth="0" marginheight="0"></iframe>').attr({
                'src': '/media/t/' + id + '/embed'
            }).appendTo($('#PlayerFrame').empty().slideDown());
            current = $(this).addClass('Release-track-selected');
        });
    {/literal}</script>
{/if}
