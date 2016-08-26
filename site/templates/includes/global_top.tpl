<div class="global-header">
    {if !empty($OPTIONS.banner)}{$OPTIONS.banner['value']}{/if}
    <div id="Loading" style="display: none;"><img src="/i/icons/loading.gif" alt="" width="16" height="16"/></div>
    <div id="Error" style="display: none;"><span id="ErrorText"></span></div>
    <script type="text/javascript">{literal}
    (function() {
        var requests = 0;
        $('#Loading').ajaxSend(function(e, r, s) {
            requests++;
            $(this).css({'left': $(window).width() / 2 - 10}).show();
        }).ajaxComplete(function(e, r, s) {
            requests--;
            if (requests == 0) {
                $(this).fadeOut('fast');
            }
        });
        $("#Error").ajaxError(function(e, r, s) {
            $("#ErrorText").text("Error requesting");
            $(this).css({'left': $(window).width() / 2 - 100}).fadeIn('fast');
        }).ajaxSend(function(e, r, s) {
            $(this).hide();
        }).click(function() {
            $(this).fadeOut('fast');
        });
    })();
    {/literal}</script>
    {if !empty($OPTIONS.new) && empty($USER)}
        <img src="/i/icons/new_big.png" width="35" height="34" alt="New!" style="float: right; margin-top: 6px;" />
    {/if}
    {include file='includes/global_login.tpl'}

    {if !empty($OPTIONS.new) && empty($USER)}
        <div class="logo-text">{$LANG.headers.new}</div>
    {/if}
    <div class="logo">
        <a class="logo-ffm" href="/"><img src="/i/decor/logo.png" alt="{$LANG.global.title}" width="159" height="72" /></a>
        <a class="logo-ffm-record-label" href="/label">
            <div>Far from Moscow Records</div>
            <img src="/i/decor/ffm-music-label.png" />
        </a>
        <a class="logo-nm" href="http://www.noisymap.com/" target="_blank"></a>
    </div>
    <div class="global-menu">
        <div class="left">
            <ul class="menu">
                <li class="menu-item{if $Section == 'home'} menu-item-selected{/if}"><a href="/"><span>{$LANG.menu.home}</span></a></li>
                <li class="menu-item{if $Section == 'artists'} menu-item-selected{/if}"><a href="/artists/"><span>{$LANG.menu.artists}</span></a></li>
                <li class="menu-item{if $Section == 'articles'} menu-item-selected{/if}"><a href="/articles/"><span>{$LANG.menu.articles}</span></a></li>
                <li class="menu-item{if $Section == 'video'} menu-item-selected{/if}"><a href="/video/"><span>{$LANG.menu.video}</span></a></li>
                <li class="menu-item{if $Section == 'label'} menu-item-selected{/if}"><a href="/label/"><span>{$LANG.menu.label}</span></a></li>
                <li class="menu-item{if $Section == 'about'} menu-item-selected{/if} menu-last"><a href="/about/"><span>{$LANG.menu.about}</span></a></li>
            </ul>
        </div>
        <div class="right">
            <table style="height: 36px;">
                <tr>
                    {if !empty($Share)}
                        {*<td style="width: 100px;"><div style="text-transform: uppercase;text-align: right;padding-right: 10px;color:#aaafb6">{$LANG.link.share}</div></td>*}
                        <td style="width: 85px;">
                            <div class="rel" id="ShareBlock">
                                <div id="SharePopup" class="search-tooltip" style="display: none;">
                                    <div>{$LANG.link.share}</div>
                                </div>
                                <a onclick="openWin($(this).attr('href'));return false;" title="{$LANG.link.facebook}" href="http://www.facebook.com/sharer.php?u={$url|escape:'url'}&t={$title|escape:'url'}"><img src="/i/icons/share_f.png" alt="" width="20" height="20" /></a>
                                <a onclick="openWin($(this).attr('href'));return false;" title="{$LANG.link.vkontakte}" href="http://vkontakte.ru/share.php?url={$url|escape:'url'}"><img src="/i/icons/share_v.png" alt="" width="20" height="20" /></a>
                                <a onclick="openWin($(this).attr('href'));return false;" title="{$LANG.link.twitter}" href="http://twitter.com/share?url={$url|escape:'url'}"><img src="/i/icons/share_t.png" alt="" width="20" height="20" /></a>
                            </div>
                        </td>
                    {/if}
                    {*<td style="color: #d6d7db;"><a href="/m/">{$LANG.menu.mobile}</a><span style="padding: 0 5px;"> | </span></td>*}
                    <td style="width: 200px; text-align: right;"><div class="rel">
                        <form action="/search/" onsubmit="$('#Q').val($('#FSearch').val());">
                            <input type="hidden" name="q" id="Q" value="" />
                            <input type="search" id="FSearch" class="search" placeholder="{$LANG.link.searchField}" results="0" accesskey="s"/>
                        </form>
                        <div id="Suggest" class="suggest" style="display: none;"></div>
                    </div></td>
                </tr>
            </table>
        </div>
    </div>
</div>
