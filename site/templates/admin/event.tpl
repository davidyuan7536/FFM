<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-geo-tags.js?{$V}"></script>
<script type="text/javascript" src="js/select-artists.js?{$V}"></script>
<script type="text/javascript" src="js/event.js?{$V}"></script>

<h1>{$Title}</h1>

<input type="hidden" id="Id" value="{$Event.event_id}" />

<div class="F-wrap" style="margin: 20px 0 30px;">
<table>
<col style="width: 150px;"/>
<tr>
    <td><label for="Name" class="F-label">Name</label></td>
    <td><input type="text" id="Name" value="{$Event.event_name|escape}" style="width: 380px;" /></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="Description" class="F-label">Description</label></td>
    <td><textarea id="Description" style="width: 380px; height: 100px;">{$Event.event_description}</textarea></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="Address" class="F-label">Address</label></td>
    <td><textarea id="Address" style="width: 380px; height: 100px;">{$Event.event_address}</textarea></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="Date" class="F-label">Date</label></td>
    <td>
        <input type="text" id="Date" value="{$Event.event_date|date_format:"%Y-%m-%d"}" style="width: 100px;" />
        &nbsp;&nbsp;Time:
        <input type="text" id="Time" value="{$Event.event_date|date_format:"%H:%M"}" style="width: 100px;" />
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td><label for="GeoTagName" class="F-label">Geotag</label></td>
    <td>
        <input type="hidden" id="GeoTagId" value="{$Event.geo_tag.geo_tag_id}" />
        <span id="GeoTagName" class="Link">{if $Event.geo_tag}{$Event.geo_tag.name}{else}None{/if}</span><img id="GeoTagBrowse" src="/site/i/icons/marker.png" alt="" width="24" height="24" style="vertical-align: -7px; cursor: pointer;" >
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr style="vertical-align: top;">
    <td style="padding-top: 7px;"><label for="SA" class="F-label">Artists</label></td>
    <td>
        <div class="SG-wrap">
            <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SA-button" />
            <div class="SG-inner" id="SA">
                {foreach from=$Event.artists item=row}
                    <div class="SA-item" uid="{$row.artist_id}"><img src="/site/i/icons/xfn.png" width="16" height="16" /> {$row.name}</div>
                {/foreach}
            </div>
        </div>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td align="right"></td>
    <td>
        <div style="padding-top: 10px;">
            <button id="Delete" class="Button Small" onclick="deleteEvent();" style="float: right;" tabindex="-1">Delete</button>
            <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
            <button id="Cancel" class="Button gray F-button" onclick="cancel()">Cancel</button>
        </div>
    </td>
</tr>
</table>
</div>

<div class="W-wrap" style="margin-bottom: 20px;" id="Uploader">
<table>
<col style="width: 230px;"/>
<tr style="vertical-align: top;">
    <td>
        <img id="Image" src="{if $Event.event_image == ''}/i/decor/placeholder-article.png{else}/thumbnails/events/{$Event.event_image}.jpg{/if}" alt="" width="210" height="130"/>
    </td>
    <td>
        <div id="placeholder"></div>
        <div id="progress"></div>
    </td>
</tr>
</table>
</div>
