<div class="global-footer">
    <div class="designby">
        <a href="http://www.creasence.com/"><span>Design by Creasence</span></a>
    </div>

    <div>&copy; 2008&ndash;{$smarty.now|date_format:"%Y"} {$LANG.global.title}. All right Reserved</div>

    <ul class="menu">
        <li class="menu-item{if $Section == 'home'} menu-item-selected{/if}"><a href="/"><span>{$LANG.menu.home}</span></a></li>
        <li class="menu-item{if $Section == 'artists'} menu-item-selected{/if}"><a href="/artists/"><span>{$LANG.menu.artists}</span></a></li>
        <li class="menu-item{if $Section == 'articles'} menu-item-selected{/if}"><a href="/articles/"><span>{$LANG.menu.articles}</span></a></li>
        <li class="menu-item{if $Section == 'video'} menu-item-selected{/if}"><a href="/video/"><span>{$LANG.menu.video}</span></a></li>
        <li class="menu-item{if $Section == 'label'} menu-item-selected{/if}"><a href="/label/"><span>{$LANG.menu.label}</span></a></li>
        <li class="menu-item{if $Section == 'about'} menu-item-selected{/if} menu-last"><a href="/about/"><span>{$LANG.menu.about}</span></a></li>
    </ul>

    <div class="rss"><a href="/rss.xml">RSS feed</a></div>
    <div class="facebook"><a href="https://www.facebook.com/pages/Far-From-Moscow/140083649358756">Facebook</a></div>
    <div class="twitter"><a href="http://twitter.com/farfrommoscow">Twitter</a></div>

    <div class="clear"></div>
</div>
