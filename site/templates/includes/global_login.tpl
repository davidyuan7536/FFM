<div class="global-login">
    <a href="javascript:void(0)" onclick="return changeLang('en')" class="{if $LANG.id == 'en'} global-lang-selected{/if}">English</a>
    <span> / </span>
    <a href="javascript:void(0)" onclick="return changeLang('ru')" class="{if $LANG.id == 'ru'} global-lang-selected{/if}">Русский</a>
    <span> | </span>
    {if $USER}
        <a href="/users/{$USER.user_hash}/">{$USER.user_name}</a>
        {*<span style="color: #727272;">{$USER.user_name}</span>*}
        <span> / </span>
        <a href="/accounts/logout">{$LANG.menu.logout}</a>
    {else}
        <a href="/" onclick="return popupLogin();">{$LANG.menu.login}</a>
        <span> / </span>
        <a href="/" onclick="return popupRegister();">{$LANG.menu.register}</a>

        <div id="popupLogin" title="{$LANG.menu.login}" style="display: none;">
            <div id="flMessage" class="F-message" style="display: none; margin-bottom: 10px;"></div>
            <form id="flForm">
            <table>
            <tr>
                <td width="100"></td>
                <td style="padding-bottom: 12px;">
                    <a href="{$FB_LOGIN}" class="login-facebook">{$LANG.auth.facebook}</a>
                </td>
            </tr>
            <tr>
                <td><label for="flLogin" class="F-label">{$LANG.auth.email}</label></td>
                <td><input type="text" name="email" id="flLogin" class="F-input" style="width: 190px;" /></td>
            </tr>
            <tr><td style="height:6px;"></td></tr>
            <tr>
                <td><label for="flPassword" class="F-label">{$LANG.auth.password}</label></td>
                <td><input type="password" id="flPassword" class="F-input" style="width: 190px;" /></td>
            </tr>
            <tr>
                <td></td>
                <td><label for="flRemember" class="F-label" style="text-align: left;"><input type="checkbox" checked="checked" id="flRemember"/>{$LANG.auth.remember}</label></td>
            </tr>
            <tr>
                <td></td>
                <td style="padding: 4px 0 12px;">
                    <input type="submit" value="{$LANG.auth.login}" id="ButtonLogin" class="F-button"/>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><a href="/accounts/forgot" style="font-size: 11px;font-weight: bold;color: #000;">{$LANG.auth.forgot}</a></td>
            </tr>
            </table>
            </form>
        </div>

        <div id="popupRegister" title="{$LANG.menu.register}" style="display: none;">
            <div id="frMessage" class="F-message" style="display: none; margin-bottom: 10px;"></div>
            <form id="frForm">
            <table>
            <tr valign="top">
                <td width="120" align="right"><label for="frName" class="F-label">{$LANG.auth.name}</label></td>
                <td><input type="text" name="name" id="frName" class="F-input" style="width: 190px;" /><div id="frmName" class="F-message" style="display: none;"></div></td>
            </tr>
            <tr valign="top">
                <td align="right"><label for="frEmail" class="F-label">{$LANG.auth.email}</label></td>
                <td><input type="text" name="email" id="frEmail" class="F-input" style="width: 190px;" /><div id="frmEmail" class="F-message" style="display: none;"></div></td>
            </tr>
            <tr valign="top">
                <td align="right"><label for="frPassword" class="F-label">{$LANG.auth.password}</label></td>
                <td><input type="password" id="frPassword" class="F-input" style="width: 190px;" /><div id="frmPassword" class="F-message" style="display: none;"></div></td>
            </tr>
            <tr valign="top">
                <td></td>
                <td><label for="frSubscribe" class="F-label" style="text-align: left;"><input type="checkbox" checked="checked" id="frSubscribe"/> {$LANG.auth.subscribe}</label></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="{$LANG.auth.register}" id="ButtonRegister" class="F-button"/>
                </td>
            </tr>
            </table>
            </form>
        </div>
    {/if}
    {*<span> | </span>*}
    {*<a href="/help/ffm2-{$LANG.id}.html" style="font-weight: bold;">{$LANG.headers.whatsnew}</a>*}

</div>
