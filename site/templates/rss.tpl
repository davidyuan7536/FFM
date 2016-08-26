<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>Far from Moscow</title>
        <link>http://www.farfrommoscow.com/</link>
        <description>"Far from Moscow" is a resource designed to promote, catalog, and consider new music from Russia, Ukraine, and Belarus, together with the Baltic nations (Latvia, Lithuania, Estonia). In total, that's eleven time zones!</description>
        <language>en-us</language>
        {foreach from=$Articles item=row}
        <item>
            <title>{$row.title|escape:'html'}</title>
            <description>{if $row.image != ''}&lt;a href="http://{$smarty.const.__FFM_HOST__}/articles/{$row.filename}.html"&gt;&lt;img src="http://{$smarty.const.__FFM_HOST__}/thumbnails/articles/{$row.image}.jpg" alt="" width="210" height="130" border="0" /&gt;&lt;br /&gt;&lt;/a&gt;{/if}{$row.description|escape:'html'}</description>
            <pubDate>{$row.date|date_format:"D, d M Y H:i:s T"}</pubDate>
            <guid>http://{$smarty.const.__FFM_HOST__}/articles/{$row.filename|escape:'url'}.html</guid>
            <link>http://{$smarty.const.__FFM_HOST__}/articles/{$row.filename|escape:'url'}.html</link>
        </item>
        {/foreach}
    </channel>
</rss>