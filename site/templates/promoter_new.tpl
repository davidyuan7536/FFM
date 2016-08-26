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

        <h1 class="H-promoter">{$LANG.promoter.titleNew}</h1>
        <div style="padding: 16px 0;">
            <div class="F-warning">{$LANG.headers.warning}</div>
        </div>
        <div>
            <span id="ProfileTitleEdit">
                <label style="font-size: 10pt;">{$LANG.promoter.labelName}: <input id="ProfileName" type="text" class="F-input" style="width: 120px;" placeholder="{$LANG.promoter.name}"/></label>
                <label style="font-size: 10pt;">{$LANG.promoter.labelNameRu}: <input id="ProfileNameRu" type="text" class="F-input" style="width: 120px;" placeholder="{$LANG.promoter.nameRu}"/></label>
            </span>
            <button class="F-button" id="ButtonSave">{$LANG.link.save}</button>
        </div>

    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
