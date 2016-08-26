<link rel="stylesheet" type="text/css" href="u.css?{$V}"/>
<link rel="stylesheet" type="text/css" href="p.css?{$V}"/>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/swfupload.js?{$V}"></script>
<script type="text/javascript" src="js/handlers.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/pictures.js?{$V}"></script>
<script type="text/javascript" src="js/article-pictures.js?{$V}"></script>
<script type="text/javascript">var PATH_PICTURES = '{$smarty.const.__FFM_PICTURES_FRONT__}';</script>

<h1>{$Title}
    <a href="/articles/{$Article.filename}.html" target="_blank"><img src="/site/i/icons/external.png" alt="" width="16" height="16" style="vertical-align: -2px;" /></a>
</h1>

<input type="hidden" id="Id" value="{$Article.article_id}" />

<div class="tabs">
    <ul class="tabs-group">
        <li class="tabs-item"><a href="article-summary.php?id={$Article.article_id}">Summary</a></li>
        <li class="tabs-item"><a href="article-content.php?id={$Article.article_id}">Text</a></li>
        <li class="tabs-item"><a href="article-content.php?lang=ru&id={$Article.article_id}"><strong>Текст</strong></a></li>
        <li class="tabs-item"><a href="article-audio.php?id={$Article.article_id}">Audio</a></li>
        <li class="tabs-item tabs-selected">Pictures</li>
    </ul>
    <div class="clear"></div>
</div>

<div class="W-wrap" style="margin-bottom: 20px; border-top: none;" id="Uploader">
    <div id="placeholder"></div>
    <div id="progress"></div>
</div>

<div id="List" class="Pic-wrap">Loading...</div>
