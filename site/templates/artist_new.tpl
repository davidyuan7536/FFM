<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
<script type="text/javascript" src="/js/artist_new.js?{$V}"></script>
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    <div class="C-left">

        {if empty($USER)}
            <h1 class="H-artist">{$LANG.sys.e005}</h1>
        {else}
            {if empty($Access)}
                <div style="padding: 25px 0;">
                    <div class="F-warning">
                        <h1 class="H-artist" style="font-size: 16px;">Внимание!</h1>
                        <div style="padding: 0.25em 0;">{$OPTIONS.artist_warning['value']}</div>
                    </div>
                </div>
            {else}
                <h1 class="H-artist">{$LANG.artist.titleNew}</h1>
                <div style="padding: 16px 0;">
                    <div class="F-warning">{$LANG.headers.warning}</div>
                </div>
                <div>
                    <span id="ProfileTitleEdit">
                        <label style="font-size: 10pt;">{$LANG.artist.labelName}: <input id="ProfileName" type="text" class="F-input" style="width: 120px;" placeholder="{$LANG.artist.name}"/></label>
                        <label style="font-size: 10pt;">{$LANG.artist.labelNameRu}: <input id="ProfileNameRu" type="text" class="F-input" style="width: 120px;" placeholder="{$LANG.artist.nameRu}"/></label>
                    </span>
                    <button class="F-button" id="ButtonSave">{$LANG.link.save}</button>
                </div>
            {/if}
        {/if}

    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
