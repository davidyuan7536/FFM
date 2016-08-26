<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="http://platform.twitter.com/anywhere.js?id=efMLFDXOVLfWswOUn4w&v=1"></script>
<script type="text/javascript" src="js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/select-geo-tags.js?{$V}"></script>
<script type="text/javascript" src="js/select-genres.js?{$V}"></script>
<script type="text/javascript" src="js/select-artists.js?{$V}"></script>
<script type="text/javascript" src="js/article-summary.js?{$V}"></script>

<h1>{$Title}</h1>

<input type="hidden" id="Id" value="{$Article.article_id}" />

<div class="tabs">
    <ul class="tabs-group">
        <li class="tabs-item tabs-selected">Summary</li>
        {if $Article.article_id != ''}
        <li class="tabs-item"><a href="article-content.php?id={$Article.article_id}">Text</a></li>
        <li class="tabs-item"><a href="article-content.php?lang=ru&id={$Article.article_id}"><strong>Текст</strong></a></li>
        <li class="tabs-item"><a href="article-audio.php?id={$Article.article_id}">Audio</a></li>
        <li class="tabs-item"><a href="article-pictures.php?id={$Article.article_id}">Pictures</a></li>
        {/if}
    </ul>
    <div class="clear"></div>
</div>

<div class="F-wrap" style="border-top: none; margin-bottom: 30px;">
<table>
<col style="width: 150px;"/>
<tr>
    <td><label for="Filename" class="F-label">Filename</label></td>
    <td>
        <input type="text" id="Filename" readonly="readonly" value="{$Article.filename}" style="width: 360px;" />
        <a href="/articles/{$Article.filename}.html" id="Preview" target="_blank"><img src="/site/i/icons/external.png" alt="" width="16" height="16" style="vertical-align: -2px;" /></a>
    </td>
</tr>
<tr>
    <td><label for="Name" class="F-label">Title</label></td>
    <td><input type="text" id="Name" value="{$Article.title|escape}" style="width: 380px;" /></td>
</tr>
<tr>
    <td><label for="NameRu" class="F-label"><strong>Заголовок</strong></label></td>
    <td><input type="text" id="NameRu" value="{$Article.title_ru|escape}" style="width: 380px;" /></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="Description" class="F-label">Description</label></td>
    <td><textarea id="Description" style="width: 380px; height: 100px;">{$Article.description}</textarea></td>
</tr>
<tr style="vertical-align: top;">
    <td><label for="DescriptionRu" class="F-label"><strong>Кратко</strong></label></td>
    <td><textarea id="DescriptionRu" style="width: 380px; height: 100px;">{$Article.description_ru}</textarea></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td><label for="GeoTagName" class="F-label">Geotag</label></td>
    <td>
        <input type="hidden" id="GeoTagId" value="{$Article.geo_tag.geo_tag_id}" />
        <span id="GeoTagName" class="Link">{if $Article.geo_tag}{$Article.geo_tag.name}{else}None{/if}</span><img id="GeoTagBrowse" src="/site/i/icons/marker.png" alt="" width="24" height="24" style="vertical-align: -7px; cursor: pointer;" >
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr style="vertical-align: top;">
    <td style="padding-top: 7px;"><label for="SG" class="F-label">Genres</label></td>
    <td>
        <div class="SG-wrap">
            <img src="/site/i/icons/plus.png" alt="" width="16" height="16" class="SG-button" />
            <div class="SG-inner" id="SG">
                {foreach from=$Article.genres item=row}
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
                {foreach from=$Article.artists item=row}
                    <div class="SA-item" uid="{$row.artist_id}"><img src="/site/i/icons/xfn.png" width="16" height="16" /> {$row.name}</div>
                {/foreach}
            </div>
        </div>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td></td>
    <td><input type="checkbox" id="Status" style="vertical-align: -1px;" {if $Article.status=='publish'}checked="checked"{/if}/><label for="Status" class="F-label">Publish</label></td>
</tr>
<tr>
    <td align="right"></td>
    <td>
        <div style="padding-top: 10px;">
            <button id="Delete" class="Button Small" onclick="deleteArticle();" style="float: right;" tabindex="-1">Delete</button>
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
        <img id="Image" src="{if $Article.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$Article.image}.jpg{/if}" alt="" width="210" height="130"/>
    </td>
    <td>
        <div id="placeholder"></div>
        <div id="progress"></div>
    </td>
</tr>
</table>
</div>
