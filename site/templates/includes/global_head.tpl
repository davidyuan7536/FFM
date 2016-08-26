<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
{if !empty($noindex)}
<meta name="robots" content="noindex" />
{/if}
{if isset($Title)}
<title>{$Title} - FFM</title>
{else}
<title>{$LANG.global.title}</title>
{/if}
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://{$smarty.const.__FFM_HOST__}/rss.xml">
{if $smarty.const.__FFM_NAME__=='PROD'}
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="icon" href="/i/favicon32.png" sizes="32x32" type="image/png" />
{else}
<link rel="shortcut icon" href="/i/favicon16-{$smarty.const.__FFM_NAME__}.png?{$V}" />
<link rel="icon" href="/i/favicon16-{$smarty.const.__FFM_NAME__}.png?{$V}" type="image/png" />
<link rel="icon" href="/i/favicon32-{$smarty.const.__FFM_NAME__}.png?{$V}" sizes="32x32" type="image/png" />
{/if}
<link rel="apple-touch-icon" href="/i/favicon57.png"/>
<link rel="apple-touch-icon" sizes="72x72" href="/i/favicon72.png"/>
<link rel="apple-touch-icon" sizes="114x114" href="/i/favicon114.png"/>
{if $smarty.const.__FFM_NAME__=='LOCAL'}
<link rel="stylesheet" type="text/css" href="/css/base.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/ui.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/home.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/player.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/articles.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/artists.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/filter.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/video.css?{$V}" media="all" />
<link rel="stylesheet" type="text/css" href="/css/label.css?{$V}" media="all" />
{else}
<link rel="stylesheet" type="text/css" href="/static/default.css?{$V}" media="all" />
{/if}
<script type="text/javascript">(new Image).src='/i/sprites.png?{$V}';</script>
{if $smarty.const.__FFM_NAME__=='LOCAL'}
<script type="text/javascript" src="/js/sources/swfobject.js?{$V}"></script>
<script type="text/javascript" src="/js/sources/jquery.js?{$V}"></script>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="/js/sources/jquery.cycle.all.js?{$V}"></script>
<script type="text/javascript" src="/js/sources/jquery.autoresize.js?{$V}"></script>
{else}
<script type="text/javascript" src="/static/lib.js"></script>
{/if}
<script type="text/javascript" src="/js/player.js?{$V}"></script>
<script type="text/javascript" src="/js/utils.js?{$V}"></script>
{$smarty.const.__FFM_TOP_CODE__}
