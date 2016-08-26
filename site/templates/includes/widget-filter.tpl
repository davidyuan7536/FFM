<div class="filter">
    <div class="filter-left">
        <strong>{$LANG.filter.genres}:</strong>
        <div>
            <a href="?genre=dance">Dance</a><span> | </span>
            <a href="?genre=electronic">Electronic</a><span> | </span>
            <a href="?genre=jazz">Jazz</a><span> | </span>
            <a href="?genre=pop">Pop</a><span> | </span>
            <a href="?genre=reggae">Reggae</a><span> | </span>
            <a href="?genre=rock">Rock</a><span> | </span>
            <span class="filter-all" onclick="showFilter(this, '#filter-genres');">
                <em></em><span>{$LANG.filter.genresAll}</span>
            </span>
        </div>
    </div>
    <div class="filter-right">
        <strong>{$LANG.filter.regions}:</strong>

        <div>
            <a href="?region=ru">Russia</a><span> | </span>
            <a href="?region=ua">Ukraine</a><span> | </span>
            <a href="?region=by">Belarus</a><span> | </span>
            <span class="filter-all" onclick="showFilter(this, '#filter-regions');">
                <em></em><span>{$LANG.filter.regionsAll}</span>
            </span>
        </div>
    </div>
    <div class="clear"></div>
    <div id="filter-genres" class="filter-extra" style="display: none;">
        <div class="filter-extra-wrap">
                {include file='includes/widget-filter-item.tpl' FilterGenre='chanson'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='easy-list'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='electronic'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='english'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='french'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='instrumental'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='latin'}
        </div>
        <div class="filter-extra-wrap">
                {include file='includes/widget-filter-item.tpl' FilterGenre='dance'}
        </div>
        <div class="filter-extra-wrap">
                {include file='includes/widget-filter-item.tpl' FilterGenre='jazz'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='pop'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='reggae'}
        </div>
        <div class="filter-extra-wrap">
                {include file='includes/widget-filter-item.tpl' FilterGenre='folk'}
                {include file='includes/widget-filter-item.tpl' FilterGenre='rock'}
        </div>
        <div class="clear"></div>
    </div>
    <div id="filter-regions" class="filter-extra" style="display: none;">
        <div class="filter-extra-wrap" style="padding-bottom: 10px;">
            <div class="filter-extra-title"><a href="?region=ru">{$GeoTags['ru'].name}</a></div>
            <ul>
                {foreach from=$GeoTags['ru'].childNodes item=i}
                    <li><a href="?region={$i.filename|escape:'url'}">{$i.name}</a></li>
                {/foreach}
            </ul>
        </div>
        <div style="float: left;">
        {$a=0}
        {foreach from=$GeoTags key=k item=v}
            {if $k != 'ru'}
            <div class="filter-extra-wrap" style="padding-bottom: 10px;">
                <div class="filter-extra-title"><a href="?region={$k|escape:'url'}">{$v.name}</a></div>
                {if !empty($v.childNodes)}
                <ul>
                    {foreach from=$v.childNodes item=i}
                        <li><a href="?region={$i.filename|escape:'url'}">{$i.name}</a></li>
                    {/foreach}
                </ul>
                {/if}
            </div>
            {$a=$a+1}
            {if !($a % 3)}<div class="clear"></div>{/if}
            {/if}
        {/foreach}
        </div>
        <div class="clear"></div>
    </div>
</div>
