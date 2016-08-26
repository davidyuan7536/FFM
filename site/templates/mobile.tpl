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
        {*<a class="button" href="#searchForm">Search</a>*}
    </div>

    <ul id="home" title="FFM" selected="true" bbclass="backButtonImg">
        <li><a href="#articles">Articles</a></li>
        <li><a href="http://farfrommoscow.bandcamp.com/" onclick="location.assign(this.href); return false;">Label</a></li>
        <li><a href="#about">About</a></li>
        <li><form action="/m/search" method="get">
            {*<label>Search:</label>*}
            <input id="search" type="text" name="search"/>
            <a type="submit" href="#">Search</a>
        </form></li>
    </ul>

    <ul id="articles" title="Articles" bbclass="backButtonImg">
        {include file='mobile-articles.tpl'}
    </ul>

    {*<div id="events" title="Events" class="page">
        {include file='mobile-events.tpl'}
    </div>*}

    <div id="about" title="About" class="page">
        <h2>About “Far from Moscow”</h2>
        <div class="C-wrap">
            <p><strong>“Far from Moscow” is a resource designed to promote, catalog, and consider new music from Russia, Ukraine, and Belarus, together with the Baltic nations (Latvia, Lithuania, Estonia). In total, that’s eleven time zones!</strong></p>
            <p>Our resource is named after a famous Soviet novel, celebrating the heroic efforts of Siberian oil workers during World War Two, an awfully long way from comfort or safety. We aim to support a similar, far-flung diligence on the “cultural front” today.</p>
            <p>The site is administered and edited by <a href="http://www.humnet.ucla.edu/humnet/slavic/faculty/macfadyen_d/macfadyen_d.html" target="_blank">David MacFadyen</a> (<a href="mailto:dmacfady@humnet.ucla.edu">dmacfady@humnet.ucla.edu</a>); it is hosted by the <a href="http://www.humnet.ucla.edu/humnet/slavic/index.html" target="_blank">Department of Slavic Languages and&nbsp;Literatures</a> at the <a href="http://www.ucla.edu/" target="_blank">University of California, Los Angeles</a>.</p>
            <p>We welcome contributions from all genres, locations, and traditions. Please contact us with media or ideas, and we’ll do all we can to help!</p>
        </div>
    </div>

    {*<form id="searchForm" class="dialog" action="/m/search" method="GET">*}
        {*<fieldset>*}
            {*<h1>Articles Search</h1>*}
            {*<a class="button leftButton" type="cancel">Cancel</a>*}
            {*<a class="button blueButton" type="submit">Search</a>*}

            {*<label>Search:</label>*}
            {*<input id="search" type="text" name="search"/>*}
        {*</fieldset>*}
    {*</form>*}

</body>
</html>
