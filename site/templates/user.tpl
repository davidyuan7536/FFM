<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
<link rel="stylesheet" type="text/css" href="/css/user.css?{$V}" media="all" />
{if $Current}<script type="text/javascript" src="/js/user.js?{$V}"></script>{/if}
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    <div class="C-left">

        <h1>{$User.user_name}</h1>

        <div style="padding: 20px 0 30px;">
            {if $Current}
                <div style="background-color: #eff3f6; padding: 10px 16px;">
                    <label for="UserSearch">{$LANG.user.findLabel}: </label>
                    <input type="search" placeholder="{$LANG.user.findText}" id="UserSearch" results="0" style="width: 350px;"/>
                </div>
                <div id="UserSuggest" class="User-suggest" style="display: none;"></div>

                <div id="popupRequest" title="{$LANG.user.request}" style="display: none;">
                    <div id="fqMessage" class="F-message" style="display: none; margin-bottom: 10px;"></div>
                    <input type="hidden" id="fqId" />
                    <form id="fqForm">
                    <table>
                    <tr>
                        <td width="100"><label class="F-label"><img width="30" height="30" id="fqImage"></label></td>
                        <td>{$LANG.user.text}: <br/><div id="fqArtist" style="font-weight: bold;"></div></td>
                    </tr>
                    <tr>
                        <td><label for="fqEmail" class="F-label">{$LANG.user.labelEmail}</label></td>
                        <td><input type="text" id="fqEmail" class="F-input" style="width: 190px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="fqText" class="F-label">{$LANG.user.labelText}</label></td>
                        <td><textarea id="fqText" class="F-input" style="width: 190px; height: 100px;"></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="{$LANG.user.buttonSend}" id="ButtonRequest" class="F-button" />
                        </td>
                    </tr>
                    </table>
                    </form>
                </div>

                <div style="border-top: 1px solid #cbd0d6;">
                    {if $USER}
                        {*<div style="float: right; padding-top: 16px;">
                            <a class="F-button" href="/artists/new/">{$LANG.link.addArtist}</a>
                            <a class="F-button" href="/promoters/new/">{$LANG.link.addPromoter}</a>
                        </div>*}
                    {/if}
                    <h2>{$LANG.user.myPages}</h2>
                    <div id="pmList" style="min-height: 100px;"></div>
                    <div class="clear"></div>
                </div>
            {/if}
        </div>
    </div>

    <div class="C-right">


    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
