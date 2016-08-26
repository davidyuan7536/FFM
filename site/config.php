<?php

/**
 * Email settings
 */
define('__FFM_EMAIL__', 'dmacfady@humnet.ucla.edu');
define('__FFM_SUPPORT__', 'dev@creasence.com');

/**
 * Every page JavaScript code 
 */
define('__FFM_TOP_CODE__', "
<script src=\"//mc.yandex.ru/metrika/watch.js\" type=\"text/javascript\"></script>
<script type=\"text/javascript\">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-16531576-3']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
try { var yaCounter1269075 = new Ya.Metrika(1269075); } catch(e){}
})();
</script>
");

/**
 * Facebook Application ID
 */
define('__FFM_FBID__', '143603618997898');
define('__FFM_FBSECRET__', 'ddc8c761dcc1cfaa57c49911bc9bfc43');
define('__FFM_ADMIN__', '809757846,1825880935,100001414873373,100002243599649');

/**
 * Database settings
 */
define('__FFM_CONNECT__', 'mysql://farfrommoscowdev:MxtaP9Gq7MHmtdz@localhost/farfrommoscowdevdb');

/**
 * Site settings
 */
define('__FFM_HOST__', 'www.farfrommoscow.com');
define('__FFM_NAME__', 'PROD');

/**
 * Directory settings
 */
define('__FFM_PICTURES__', dirname(__FILE__) . '/../wp-content/uploads/');
define('__FFM_PICTURES_FRONT__', '/wp-content/uploads/');
define('__FFM_AUDIO__', dirname(__FILE__) . '/../audio/');
define('__FFM_AUDIO_FRONT__', '/audio/');
define('__FFM_ARCHIVE__', dirname(__FILE__) . '/../archive/');
define('__FFM_ARCHIVE_FRONT__', '/archive/');
define('__FFM_PROFILE__', dirname(__FILE__) . '/../pictures/a/');
define('__FFM_PROFILE_FRONT__', '/pictures/a/');
define('__FFM_PROMOTER__', dirname(__FILE__) . '/../pictures/p/');
define('__FFM_PROMOTER_FRONT__', '/pictures/p/');

/**
 * Internal constants
 */
define('URL', 'url');
define('HANDLER', 'handler');

?>
