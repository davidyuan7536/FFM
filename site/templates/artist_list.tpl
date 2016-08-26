<div class="Release-artists-wrapper">
    {foreach from=$artistsList item=artist}
        <div class="suggest-wrap">
            <div class="suggest-name"><a href="/artists/{$artist['filename']}.html"><span></span><img width="50" height="50" alt="" src="{$artist['image']}">&nbsp;{$artist['name']|escape}</a></div>
            <div class="clear"></div>
        </div>
    {/foreach}
</div>