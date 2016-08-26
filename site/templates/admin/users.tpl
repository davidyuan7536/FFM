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
    <th width="200"><div style="position: relative;">Email <form action="users.php" method="get"><input type="search" class="F-search" name="ESearch" value="{$ESearch|escape}" placeholder="by email" results="8" accesskey="e" /></form></div></th>
    <th><div style="position: relative;">Name <form action="users.php" method="get"><input type="search" class="F-search" name="NSearch" value="{$NSearch|escape}" placeholder="by name" results="8" accesskey="n" /></form></div></th>
    <th width="120">Place</th>
    <th width="60">Id</th>
    <th width="50">News</th>
    <th width="50">Status</th>
</tr>
{foreach from=$Users item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}" {if $row.enabled != 1}style="background-color: #fdd; color: #d00"{/if}>
        <td><div class="Trunc Small"><a href="/site/admin/user.php?id={$row.user_id}"><img src="/site/i/icons/xfn-sweetheart.png" width="16" height="16" alt="" /> {$row.user_email|escape}</a></div></td>
        <td><div class="Trunc">{$row.user_name}</div></td>
        <td><div class="Trunc">{if $row.country_code}
            <a href="http://maps.google.com/maps?sll={$row.latitude},{$row.longitude}&q={$row.latitude},{$row.longitude}" target="_blank"><img src="/i/flags/{$row.country_code|lower}.png" alt="" width="16" height="11" style="vertical-align: 0;"/> {$row.city}, {$row.country_code}</a>
        {/if}</div></td>
        <td><div class="Trunc Small">{$row.user_hash}</div></td>
        <td><div class="Trunc Small">{if $row.subscribe == 1}Yes{/if}</div></td>
        <td><div class="Trunc Small">{if $row.enabled == 1}
            Active
        {else}
            Inactive
        {/if}</div></td>
    </tr>
{/foreach}

</table>
</div>

<div class="P-wrap">
{include file='includes/widget-pages-links.tpl'}
</div>

