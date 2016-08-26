<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-geo-tags.js?{$V}"></script>
<script type="text/javascript" src="js/select-genres.js?{$V}"></script>
<script type="text/javascript" src="js/artist.js?{$V}"></script>

<h1>{$Title}</h1>

<input type="hidden" id="Id" value="{$Artist.artist_id}" />

<div class="F-wrap" style="margin-bottom: 20px;">
<table>
<col style="width: 150px;"/>
<tr>
    <td><label for="Filename" class="F-label">Filename</label></td>
    <td>
        <input type="text" id="Filename" readonly="readonly" value="{$Artist.filename}" style="width: 280px;" />
        <a href="/artists/{$Artist.filename}.html" id="Preview" target="_blank"><img src="/site/i/icons/external.png" alt="" width="16" height="16" style="vertical-align: -2px;" /></a>
    </td>
</tr>
<tr>
    <td><label for="Name" class="F-label">Name</label></td>
    <td><input type="text" id="Name" value="{$Artist.name|escape}" style="width: 300px;" /></td>
</tr>
<tr>
    <td><label for="NameRu" class="F-label">Имя по-русски</label></td>
    <td><input type="text" id="NameRu" value="{$Artist.name_ru|escape}" style="width: 300px;" /></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="Description" class="F-label">Description</label></td>
    <td><textarea id="Description" style="width: 300px; height: 100px;">{$Artist.description}</textarea></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="Links" class="F-label">Links</label></td>
    <td><textarea id="Links" style="width: 300px; height: 100px;">{$Artist.links}</textarea></td>
</tr>
<tr>
    <td><label for="GeoTagName" class="F-label">Geotag</label></td>
    <td>
        <input type="hidden" id="GeoTagId" value="{$Artist.geo_tag.geo_tag_id}" />
        <span id="GeoTagName" class="Link">{if $Artist.geo_tag}{$Artist.geo_tag.name}{else}None{/if}</span><img id="GeoTagBrowse" src="/site/i/icons/marker.png" alt="" width="24" height="24" style="vertical-align: -7px; cursor: pointer;" >
    </td>
</tr>
<tr style="vertical-align: top;">
    <td style="padding-top: 7px;"><label for="SG" class="F-label">Genres</label></td>
    <td>
        <div class="SG-wrap">
            <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SG-button" />
            <div class="SG-inner" id="SG">
                {foreach from=$Artist.genres item=row}
                    <div class="SG-item" uid="{$row.genre_id}">{$row.name}</div>
                {/foreach}
            </div>
        </div>
    </td>
</tr>
<tr>
    <td align="right"></td>
    <td>
        <div style="padding-top: 10px;">
            <button id="Delete" class="Button Small" onclick="deleteArtist();" style="float: right;" tabindex="-1">Delete</button>
            <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
            <button id="Cancel" class="Button gray F-button" onclick="cancel()">Cancel</button>
        </div>
    </td>
</tr>
</table>
</div>

<div class="W-wrap" style="margin-bottom: 20px;" id="Uploader">
<table>
<col style="width: 150px;"/>
<tr style="vertical-align: top;">
    <td>
        <img id="Image" src="{$Artist|artist_picture}" alt="" width="130" height="130"/>
    </td>
    <td>
        <div id="placeholder"></div>
        <div id="progress"></div>
    </td>
</tr>
</table>
</div>
