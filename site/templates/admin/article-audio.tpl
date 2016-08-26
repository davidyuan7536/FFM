<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/article-audio.js?{$V}"></script>

<style type="text/css">{literal}
.File-suggested,
.File-wrap {
    margin: 0 0 6px;
    padding: 2px 4px;
    background-color: #fafafa;
    border: 1px solid #cbd0d6;
}

.File-suggested {
    margin: 6px 10px 0 0;
    float: left;
}

.File-suggested span {
    text-decoration: underline;
    cursor: pointer;
}
{/literal}</style>

<h1>{$Title}
    <a href="/articles/{$Article.filename}.html" target="_blank"><img src="/site/i/icons/external.png" alt="" width="16" height="16" style="vertical-align: -2px;" /></a>
</h1>

<input type="hidden" id="Id" value="{$Article.article_id}" />

<div class="tabs">
    <ul class="tabs-group">
        <li class="tabs-item"><a href="article-summary.php?id={$Article.article_id}">Summary</a></li>
        <li class="tabs-item"><a href="article-content.php?id={$Article.article_id}">Text</a></li>
        <li class="tabs-item"><a href="article-content.php?lang=ru&id={$Article.article_id}"><strong>Текст</strong></a></li>
        <li class="tabs-item tabs-selected">Audio</li>
        <li class="tabs-item"><a href="article-pictures.php?id={$Article.article_id}">Pictures</a></li>
    </ul>
    <div class="clear"></div>
</div>

<div class="W-wrap" style="border-top: none; margin-bottom: 30px;">
    <div style="padding-bottom: 10px;">
        <input type="search" id="Search" placeholder="search" results="0" accesskey="s" />
    </div>
    <div id="Suggest"></div>
    <div  class="clear"></div>
</div>

<div id="List" style="padding-bottom: 20px;">Loading...</div>
