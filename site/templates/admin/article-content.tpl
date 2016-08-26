<link rel="stylesheet" type="text/css" href="p.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/article-content.js?{$V}"></script>
<script type="text/javascript" src="tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/pictures.js?{$V}"></script>
<script type="text/javascript">var PATH_PICTURES = '{$smarty.const.__FFM_PICTURES_FRONT__}';</script>

<style type="text/css">{literal}
.Editor {
    width: 670px;
    height: 350px;
}
#List {
    height: 350px;
    overflow: auto;
    padding-right: 8px;
}
{/literal}</style>

<h1>{$Title}
    <a href="/articles/{$Article.filename}.html" target="_blank"><img src="/site/i/icons/external.png" alt="" width="16" height="16" style="vertical-align: -2px;" /></a>
</h1>

<input type="hidden" id="Id" value="{$Article.article_id}" />
<input type="hidden" id="Lang" value="{$Lang}" />

<div class="tabs">
    <ul class="tabs-group">
        <li class="tabs-item"><a href="article-summary.php?id={$Article.article_id}">Summary</a></li>
        {if $Lang == 'ru'}
            <li class="tabs-item"><a href="article-content.php?id={$Article.article_id}">Text</a></li>
            <li class="tabs-item tabs-selected"><strong>Текст</strong></li>
        {else}
            <li class="tabs-item tabs-selected">Text</li>
            <li class="tabs-item"><a href="article-content.php?lang=ru&id={$Article.article_id}"><strong>Текст</strong></a></li>
        {/if}
        <li class="tabs-item"><a href="article-audio.php?id={$Article.article_id}">Audio</a></li>
        <li class="tabs-item"><a href="article-pictures.php?id={$Article.article_id}">Pictures</a></li>
    </ul>
    <div class="clear"></div>
</div>

<div style="padding: 16px 0;">
    <div style="padding-bottom: 10px;">
        <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
    </div>
    <div id="EditorLoading">Loading...</div>
    <div id="EditorWrap" style="visibility: hidden; min-height: 400px;">
        {if $Lang == 'ru'}
            <textarea id="Editor" rows="15" cols="80" class="Editor">{content}{$Article.content_ru}{/content}</textarea>
        {else}
            <textarea id="Editor" rows="15" cols="80" class="Editor">{content}{$Article.content}{/content}</textarea>
        {/if}
    </div>
</div>

<div id="MovieDialog" title="Movie" style="display: none;">
    <table>
    <col style="width: 100px;"/>
    <tr>
        <td><label for="Url" class="F-label">URL</label></td>
        <td><input type="text" id="Url" style="width: 300px;" /></td>
    </tr>
    <tr style="vertical-align: top;">
        <td><label for="Code" class="F-label"><strong>OR</strong><br />HTML-code</label></td>
        <td><textarea id="Code" style="width: 300px; height: 200px;"></textarea></td>
    </tr>
    <tr>
        <td align="right" height=40></td>
        <td>
            <div>
                <button id="MovieInsert" class="Button blue F-button">Insert</button>
                <button id="MovieCancel" class="Button gray F-button">Cancel</button>
            </div>
        </td>
    </tr>
    </table>
</div>

<div id="PicturesDialog" title="Pictures" style="display: none;">
    <div class="Filter">
        <table>
            <tr>
                <td><select id="Years"></select></td>
            </tr>
        </table>
    </div>

    <div style="padding: 10px 0;">
        <div id="List" class="Pic-wrap"></div>
    </div>

    <table>
    <tr>
        <td>
            <div>
                <button id="PicturesInsert" class="Button blue F-button">Insert</button>
                <button id="PicturesCancel" class="Button gray F-button">Cancel</button>
            </div>
        </td>
    </tr>
    </table>
</div>
