<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Far from Moscow</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<link rel="icon" type="image/png" href="/i/favicon32.png">
<link rel="apple-touch-icon" href="/i/favicon57.png"/>
<link rel="apple-touch-icon" sizes="72x72" href="/i/favicon72.png"/>
<link rel="apple-touch-icon" sizes="114x114" href="/i/favicon114.png"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="stylesheet" href="/css/mobile.css?{$V}" type="text/css" />
<link rel="stylesheet" href="/mobile/default.css?{$V}" type="text/css" />
<script type="application/x-javascript" src="/js/sources/iui.js?{$V}"></script>
<script type="text/javascript">{literal}
	iui.animOn = true;
{/literal}</script>
{$smarty.const.__FFM_TOP_CODE__}
</head>

<body>
    <div class="toolbar">
        <h1 id="pageTitle"></h1>
        <a id="backButton" class="button" href="#"></a>
    </div>

    <div id="home" title="Audio Player" selected="true" style="padding: 0 5px;">
        {foreach from=$Audios item=row}
            <div style="padding: 10px 0;">
                <div><strong>{$row.artist.name}</strong> - {$row.audio_name}</div>
                <div style="overflow: hidden;"><audio src="{$smarty.const.__FFM_AUDIO_FRONT__}{$row.audio_filename}" controls></audio></div>
            </div>
        {/foreach}
    </div>

</body>
</html>
