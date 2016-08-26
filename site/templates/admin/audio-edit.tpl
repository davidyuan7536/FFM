<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-artists.js?{$V}"></script>
<script type="text/javascript" src="js/audio-edit.js?{$V}"></script>

<h1>{$Title} / <small>{$Audio.audio_filename}</small> <img src="/site/i/icons/wand-big.png" alt="" width="24" height="24" id="Wand" style="vertical-align: -5px; cursor: pointer;" /></h1>

<input type="hidden" id="Id" value="{$Audio.audio_id}" />

<div class="F-wrap" style="margin: 20px 0 30px;">
<table>
<col style="width: 150px;"/>
<tr>
    <td><label for="Name" class="F-label">Name</label></td>
    <td><input type="text" id="Name" value="{$Audio.audio_name|escape}" style="width: 380px;" /></td>
</tr>
<tr>
    <td></td>
    <td><div id="NameHelper" class="Helper" style="display: none;"></div></td>
</tr>
<tr>
    <td><label for="AudioAlbum" class="F-label">Album</label></td>
    <td><input type="text" id="AudioAlbum" value="{$Audio.audio_album|escape}" style="width: 380px;" /></td>
</tr>
<tr>
    <td></td>
    <td><div id="AlbumHelper" class="Helper" style="display: none;"></div></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr style="vertical-align: top;">
    <td style="padding-top: 7px;"><label for="SA" class="F-label">Artist</label></td>
    <td>
        <div class="SG-wrap">
            <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SA-button" />
            <div class="SG-inner" id="SA">
                {if $Audio.artist_id != 0}
                    <div class="SA-item" uid="{$Audio.artist_id}"><img src="/site/i/icons/xfn.png" width="16" height="16" /> {$Audio.artist.name|escape}</div>
                {/if}
            </div>
        </div>
    </td>
</tr>
<tr>
    <td></td>
    <td><div id="ArtistHelper" class="Helper" style="display: none;"></div></td>
</tr>
<tr>
    <td></td>
    <td>
        <div style="padding: 16px 0;">
            <a href="{$smarty.const.__FFM_AUDIO_FRONT__}{$Audio.audio_filename}">{$Audio.audio_filename}</a>        
        </div>
        <div>
            <audio src="{$smarty.const.__FFM_AUDIO_FRONT__}{$Audio.audio_filename}" controls="controls">
                Your browser does not support the <code>audio</code> element.
            </audio>
        </div>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td align="right"></td>
    <td>
        <div style="padding-top: 10px;">
            <button id="Delete" class="Button Small" onclick="deleteAudio();" style="float: right;" tabindex="-1">Delete</button>
            <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
            <button id="Cancel" class="Button gray F-button" onclick="cancel()">Cancel</button>
        </div>
    </td>
</tr>
</table>
</div>
