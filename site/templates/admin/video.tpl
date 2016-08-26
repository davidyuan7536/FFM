<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-artists.js?{$V}"></script>
<script type="text/javascript" src="js/video.js?{$V}"></script>

<h1>{$Title}</h1>

<input type="hidden" id="Id" value="{$Video.video_id}" />

<div class="F-wrap" style="margin: 20px 0 30px;">
<table>
<col style="width: 150px;"/>
<tr>
    <td><label for="Name" class="F-label">Name</label></td>
    <td><input type="text" id="Name" value="{$Video.video_name|escape}" style="width: 380px;" /></td>
</tr>
<tr>
    <td><label for="Url" class="F-label">Video link</label></td>
    <td><input type="text" id="Url" value="" style="width: 380px;" /></td>
</tr>
<tr>
    <td><label for="ServiceName" class="F-label">Service</label></td>
    <td><input type="text" id="ServiceName" value="{$Video.service_name|escape}" readonly="readonly" style="width: 100px;" /></td>
</tr>
<tr>
    <td><label for="ServiceId" class="F-label">Id</label></td>
    <td><input type="text" id="ServiceId" value="{$Video.service_id|escape}" readonly="readonly" style="width: 100px;" /></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr style="vertical-align: top;">
    <td style="padding-top: 7px;"><label for="SA" class="F-label">Artist</label></td>
    <td>
        <div class="SG-wrap">
            <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SA-button" />
            <div class="SG-inner" id="SA">
                {if $Video.artist_id != 0}
                    <div class="SA-item" uid="{$Video.artist_id}"><img src="/site/i/icons/xfn.png" width="16" height="16" /> {$Video.artist.name|escape}</div>
                {/if}
            </div>
        </div>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td align="right"></td>
    <td>
        <div style="padding-top: 10px;">
            <button id="Delete" class="Button Small" onclick="deleteVideo();" style="float: right;" tabindex="-1">Delete</button>
            <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
            <button id="Cancel" class="Button gray F-button" onclick="cancel()">Cancel</button>
        </div>
    </td>
</tr>
</table>
</div>
