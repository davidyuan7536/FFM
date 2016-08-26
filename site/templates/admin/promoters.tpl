<script type="text/javascript" src="js/utils.js?{$V}"></script>

<h1>{$Title}</h1>

<script type="text/javascript">{literal}
$(document).ready(function(){
	imagePreview();
});
{/literal}</script>

<style type="text/css">{literal}
{/literal}</style>

<div style="">
<table class="T-panel">
<col />
<tr>
    <th width="200"><div style="position: relative;">Name <form action="promoters.php" method="get"><input type="search" class="F-search" name="NSearch" value="{$NSearch|escape}" placeholder="by name" results="8" accesskey="n" /></form></div></th>
    <th>Description</th>
    <th width="100">Status</th>
    <th width="100">Actions</th>
</tr>
{foreach from=$Promoters item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}">
        <td><div class="Trunc"><a href="/promoters/{$row.promoter_filename}.html" target="_blank"><img src="/site/i/icons/xfn-promoter.png" width="16" height="16" alt="" /> {$row.promoter_name|escape}<img src="/site/i/icons/external-small.png" alt="" width="16" height="16" /></a></div></td>
        <td><div class="Trunc">{$row.promoter_description}</div></td>
        <td><div class="Trunc">{if $row.promoter_status == $smarty.const.PROMOTER_STATUS_PERSON}
            Person
        {else}
            Club
        {/if}</div></td>
        <td><form method="post" action="promoters.php" onsubmit="return confirm('Do you really want to delete this promoter?')">
                <input type="hidden" name="Id" value="{$row.promoter_id}">
                <input type="hidden" name="Action" value="delete">
                <button type="submit" style="font-size: 9px;" class="Button Small">Delete</button>
            </form></td>
    </tr>
{/foreach}

</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>

