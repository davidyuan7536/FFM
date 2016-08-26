<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="http://platform.twitter.com/anywhere.js?id=efMLFDXOVLfWswOUn4w&v=1"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-geo-tags.js?{$V}"></script>
<script type="text/javascript" src="js/select-genres.js?{$V}"></script>
<script type="text/javascript" src="js/select-artists.js?{$V}"></script>
<script type="text/javascript" src="js/release.js?{$V}"></script>

<h1>{$Title}</h1>

<input type="hidden" id="Id" value="{$Release.release_id}" />

<div class="tabs">
    <ul class="tabs-group">
        <li class="tabs-item tabs-selected">Release</li>
    </ul>
    <div class="clear"></div>
</div>

<div class="F-wrap" style="border-top: none; margin-bottom: 30px;">
    <table>
        <col style="width: 150px;"/>
        <tr>
            <td><label for="Filename" class="F-label">Filename</label></td>
            <td>
                <input type="text" id="Filename" readonly="readonly" value="{$Release.filename}" style="width: 360px;" />
                <a href="/label/{$Release.filename}.html" id="Preview" target="_blank"><img src="/site/i/icons/external.png" alt="" width="16" height="16" style="vertical-align: -2px;" /></a>
            </td>
        </tr>
        <tr>
            <td><label for="FFM_id" class="F-label">Release Id</label></td>
            <td><input type="text" id="FFM_id" value="{$Release.ffm_id}" style="width: 380px;" /></td>
        </tr>
        <tr>
            <td><label for="Name" class="F-label">Title</label></td>
            <td><input type="text" id="Name" value="{$Release.title|escape}" style="width: 380px;" /></td>
        </tr>
        <tr>
            <td><label for="NameRu" class="F-label"><strong>Заголовок</strong></label></td>
            <td><input type="text" id="NameRu" value="{$Release.title_ru|escape}" style="width: 380px;" /></td>
        </tr>
        <tr style="vertical-align: top;">
            <td><label for="Description" class="F-label">Description</label></td>
            <td><textarea id="Description" style="width: 380px; height: 100px;">{$Release.description}</textarea></td>
        </tr>
        <tr style="vertical-align: top;">
            <td><label for="DescriptionRu" class="F-label"><strong>Кратко</strong></label></td>
            <td><textarea id="DescriptionRu" style="width: 380px; height: 100px;">{$Release.description_ru}</textarea></td>
        </tr>
        <tr style="vertical-align: top;">
            <td><label for="PlayerForList" class="F-label">Player for list</label></td>
            <td><textarea id="PlayerForList" style="width: 380px; height: 100px;">{$Release.player_for_list}</textarea></td>
        </tr>
        <tr style="vertical-align: top;">
            <td><label for="PlayerForPage" class="F-label">Player for page</label></td>
            <td><textarea id="PlayerForPage" style="width: 380px; height: 100px;">{$Release.player_for_page}</textarea></td>
        </tr>
        <tr style="vertical-align: top;">
            <td><label for="DownloadLink" class="F-label">Download link</label></td>
            <td><textarea id="DownloadLink" style="width: 380px; height: 100px;">{$Release.download_link}</textarea></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td><label for="GeoTagName" class="F-label">Geotag</label></td>
            <td>
                <input type="hidden" id="GeoTagId" value="{$Release.geo_tag.geo_tag_id}" />
                <span id="GeoTagName" class="Link">{if $Release.geo_tag}{$Release.geo_tag.name}{else}None{/if}</span><img id="GeoTagBrowse" src="/site/i/icons/marker.png" alt="" width="24" height="24" style="vertical-align: -7px; cursor: pointer;" >
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr style="vertical-align: top;">
            <td style="padding-top: 7px;"><label for="SG" class="F-label">Genres</label></td>
            <td>
                <div class="SG-wrap">
                    <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SG-button" />
                    <div class="SG-inner" id="SG">
                        {foreach from=$Release.genres item=row}
                            <div class="SG-item" uid="{$row.genre_id}">{$row.name}</div>
                        {/foreach}
                    </div>
                </div>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr style="vertical-align: top;">
            <td style="padding-top: 7px;"><label for="SA" class="F-label">Artists</label></td>
            <td>
                <div class="SG-wrap">
                    <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SA-button" />
                    <div class="SG-inner" id="SA">
                        {foreach from=$Release.artists item=row}
                            <div class="SA-item" uid="{$row.artist_id}"><img src="/site/i/icons/xfn.png" width="16" height="16" /> {$row.name}</div>
                        {/foreach}
                    </div>
                </div>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td></td>
            <td><input type="checkbox" id="Status" style="vertical-align: -1px;" {if $Release.status=='publish'}checked="checked"{/if}/><label for="Status" class="F-label">Publish</label></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <div style="padding-top: 10px;">
                    <button id="Delete" class="Button Small" onclick="deleteRelease();" style="float: right;" tabindex="-1">Delete</button>
                    <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
                    <button id="Cancel" class="Button gray F-button" onclick="cancel()">Cancel</button>
                </div>
            </td>
        </tr>
    </table>
</div>
