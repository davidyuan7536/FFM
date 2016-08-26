<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/about.js?{$V}"></script>
<script type="text/javascript" src="tiny_mce/jquery.tinymce.js"></script>
<style type="text/css">{literal}
    .Editor {
        height: 350px;
    }
{/literal}</style>

<h1>{$Title}</h1>

<div class="F-wrap" style="margin-bottom: 30px;">
    <table>
        <col style="width: 150px;"/>
        {*<tr>
            <td><label for="AboutHeader" class="F-label">About header</label></td>
            <td><input type="text" id="AboutHeader" value="{$About.about_header}" style="width: 380px;" /></td>
        </tr>
        <tr>
            <td><label for="AboutHeaderRu" class="F-label"><strong>Заголовок</strong></label></td>
            <td><input type="text" id="AboutHeaderRu" value="{$About.about_header_ru}" style="width: 380px;" /></td>
        </tr>*}
        <tr style="vertical-align: top;">
            <td><label for="About" class="F-label">About</label></td>
            <td>
                <div class="EditorLoading">Loading...</div>
                <div class="EditorWrap" style="visibility: hidden; min-height: 400px;">
                    <textarea id="About" rows="15" cols="80" class="Editor">{$About.about}</textarea>
                </div>
            </td>
        </tr>
        <tr style="vertical-align: top;">
            <td><label for="AboutRu" class="F-label"><strong>О нас</strong></label></td>
            <td>
                <div class="EditorLoading">Loading...</div>
                <div class="EditorWrap" style="visibility: hidden; min-height: 400px;">
                    <textarea id="AboutRu" rows="15" cols="80" class="Editor">{$About.about_ru}</textarea>
                </div>
            </td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <div style="padding-top: 10px;">
                    <button id="Save" class="Button blue F-button">Save</button>
                </div>
            </td>
        </tr>
    </table>
</div>
