<script type="text/javascript" src="js/utils.js?{$V}"></script>

<h1>{$Title}</h1>

<div style="padding: 2px 0 4px;">
    <table>
        <tr>
            <td><a href="article-summary.php" class="Button Small">Add Article</a></td>
        </tr>
    </table>
</div>

<script type="text/javascript">{literal}
$(document).ready(function(){
	imagePreview();
});
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div style="padding: 8px 0 0;">
<table class="T-panel">
<col style="width: 30px;" />
<col style="width: 50px;" />
<col style="width: 350px;" />
<col style="width: 25px;" />
<col style="width: 35px;" />
<col style="width: 160px;" />
<tr>
    <th colspan="2">Status</th>
    <th><div style="position: relative;">Title <form action="articles.php" method="get"><input type="search" class="F-search" name="search" value="{$Search|escape}" placeholder="search" results="8" accesskey="s" /></form></div></th>
    <th></th>
    <th></th>
    <th>Date</th>
</tr>
{foreach from=$Articles item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><div style="text-align: center;"><img src="/site/i/icons/{if $row.status=='publish'}status.png{else}status-away.png{/if}" alt="" width="16" height="16" /></div></td>
        <td><span><img src="{if $row.image == ''}/i/decor/placeholder-article.png{else}/thumbnails/articles/{$row.image}.jpg{/if}" class="Thumbnail" alt="" width="42" height="26"/></span></td>
        <td><div class="Trunc"><a href="article-summary.php?id={$row.article_id}">{$row.title}</a></div></td>
        <td><a href="/articles/{$row.filename}.html" target="_blank"><img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a></td>
        <td>{if $row.geo_tag_id != 0}<img src="/site/i/icons/marker-small.png" alt="" width="16" height="16" />{/if}</td>
        <td title="Modified: {$row.modified|date_format:"%d-%b-%Y %I:%M %p"}"><div>{$row.date|date_format:"%d-%b-%Y %I:%M %p"}</div></td>
    </tr>
{/foreach}
</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>