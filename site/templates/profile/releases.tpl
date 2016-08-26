{if $Editable}
<div class="Release-add">
    <button class="F-button" onclick="editRelease();">{$LANG.link.addRelease}</button>
</div>
{/if}
<div class="Release-wrap">
    <div id="ReleaseList" class="Release-grid">{include file='profile/releases_list.tpl'}</div>
    <div class="clear"></div>
</div>
<div id="TrackList" class="Release-tracks" style="display: none;"></div>
{if $Editable}
<div id="ReleaseWindow" title="{$LANG.release.releaseEdit}" style="display: none;">
    <input type="hidden" id="ReleaseId" />
    <input type="hidden" id="ReleaseDeleteConfirmation" value="{$LANG.release.releaseDeleteConfirmation}" />
    <table>
    <tr style="vertical-align: top;">
        <td style="width: 160px;">
            <div id="ReleaseImageWrap" style="position: relative;"><span style="position: absolute; display: block; left: 0; top: 0; width: 130px; height: 130px;"><span id="ReleaseImageUploaderHolder"></span></span><img src="/i/decor/placeholder-release_m.png" origin="/i/decor/placeholder-release_m.png" id="ReleaseImage" alt="" width="130" height="130"/><div id="ReleaseImageUploaderStatus"></div></div>
            <div id="ReleaseImageNotification" style="width: 140px; display: none;">{$LANG.release.releaseImageUploadNotification}</div>
        </td>
        <td>
            <table>
            <colgroup>
                <col style="width: 90px;">
            </colgroup>
            <tr>
                <td><label class="F-label">{$LANG.release.name}:</label></td>
                <td><input type="text" class="F-input" id="ReleaseName" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.release.year}:</label></td>
                <td><input type="text" class="F-input" id="ReleaseYear" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.release.label}:</label></td>
                <td><input type="text" class="F-input" id="ReleaseLabel" style="width: 200px;" /></td>
            </tr>
            <tr style="vertical-align: bottom;">
                <td><label class="F-label">{$LANG.release.genres}:</label></td>
                <td><div id="ReleaseGList" style="height: 70px; overflow: auto; width: 200px; line-height: 1.8em; border: 1px solid #cbd0d6; margin-bottom: 4px; padding: 1px 3px;"></div>
                    <input type="text" class="F-input" id="ReleaseGSearch" placeholder="{$LANG.link.searchField}" style="width: 200px;" /></td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
    <div class="clear"></div>
    <div style="padding-top: 20px; text-align: center;">
         <button class="F-button" id="ReleaseSave">{$LANG.link.save}</button>
         <button class="F-button" id="ReleaseCancel">{$LANG.link.cancel}</button>
    </div>
</div>
<div id="TrackWindow" title="{$LANG.track.trackEdit}" style="display: none;">
    <input type="hidden" id="TrackId" />
    <input type="hidden" id="TrackDeleteConfirmation" value="{$LANG.track.trackDeleteConfirmation}" />
    <table>
    <tr style="vertical-align: top;">
        <td>
            <table>
            <colgroup>
                <col style="width: 150px;">
            </colgroup>
            <tr>
                <td></td>
                <td style="height: 70px;">
                    <div id="TrackAudio"></div>
                    <div id="TrackUploadWrap" style="position: relative;">
                        <div style="height: 16px;text-decoration: underline;"><span style="position: absolute; display: block; left: 0; top: 0; width: 80px; height: 16px;"><span id="TrackUploadHolder"></span></span>Upload mp3</div>
                        <div id="TrackUploadStatus"></div>
                    </div>
                    <div id="TrackUploadNotification" style="width: 140px; display: none;">{$LANG.track.trackUploadNotification}</div>
                </td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.track.name}:</label></td>
                <td><input type="text" class="F-input" id="TrackName" maxlength="200" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.track.year}:</label></td>
                <td><input type="text" class="F-input" id="TrackYear" maxlength="4" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.track.label}:</label></td>
                <td><input type="text" class="F-input" id="TrackLabel" maxlength="200" style="width: 200px;" /></td>
            </tr>
            <tr style="vertical-align: bottom;">
                <td><label class="F-label">{$LANG.track.genres}:</label></td>
                <td><div id="TrackGList" style="height: 70px; overflow: auto; width: 200px; line-height: 1.8em; border: 1px solid #cbd0d6; margin-bottom: 4px; padding: 1px 3px;"></div>
                    <input type="text" class="F-input" id="TrackGSearch" placeholder="{$LANG.link.searchField}" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.track.keywords}:</label></td>
                <td><input type="text" class="F-input" id="TrackKeywords" maxlength="200" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td><label class="F-label">{$LANG.track.description}:</label></td>
                <td><textarea class="F-input" id="TrackDescription" style="width: 200px; height: 100px"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><label class="F-label" style="text-align: left;"><input type="checkbox" id="TrackShare" /> {$LANG.track.share}</label></td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
    <div class="clear"></div>
    <div style="padding-top: 20px; text-align: center;">
         <button class="F-button" id="TrackSave">{$LANG.link.save}</button>
         <button class="F-button" id="TrackCancel">{$LANG.link.cancel}</button>
    </div>
</div>
<script type="text/javascript">{literal}initReleaseForm();{/literal}</script>
{/if}
