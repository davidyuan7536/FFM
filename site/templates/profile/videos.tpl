<div id="AllVideosInner">
{foreach from=$Videos item=row name=videos}
    <div style="padding-bottom: 16px;;float: left;width: 300px;margin-right: 20px;">
        {if $row.filename}
            <div class="V-name">{if $row.artist_id != 0}<a href="/artists/{$row.filename}.html">{$row.name|escape}</a>
                &ndash; {/if}<span title="{$row.video_name}">{$row.video_name}</span></div>
        {else}
            <div class="V-name"><span title="{$row.video_name}">{$row.video_name}</span></div>
        {/if}
        <div>
            {if $row.service_name == 'youtube'}
                <iframe src="http://www.youtube.com/embed/{$row.service_id}" width="300" height="200" frameborder="0"></iframe>
                {elseif $row.service_name == 'vimeo'}
                <iframe src="http://player.vimeo.com/video/{$row.service_id}" width="300" height="200" frameborder="0"></iframe>
            {/if}
        </div>
    </div>
{/foreach}
<div class="clear"></div>
</div>