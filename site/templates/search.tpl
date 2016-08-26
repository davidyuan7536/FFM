<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
<script type="text/javascript" src="/js/filter.js"></script>
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">
    <div class="C-left">
        {if $LANG.id == 'en'}
        <script>{literal}
            (function() {
                var cx = '014526325747341029206:cfcuovbtk04';

                var gcse = document.createElement('script');
                gcse.type = 'text/javascript';
                gcse.async = true;
                gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                        '//www.google.com/cse/cse.js?cx=' + cx;
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(gcse, s);
            })();
        {/literal}</script>
        {elseif $LANG.id == 'ru'}
            <script>{literal}
                (function() {
                    var cx = '014526325747341029206:d0iyjiknnru';
                    var gcse = document.createElement('script');
                    gcse.type = 'text/javascript';
                    gcse.async = true;
                    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                            '//www.google.com/cse/cse.js?cx=' + cx;
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(gcse, s);
                })();
            {/literal}</script>
        {/if}
        <gcse:search></gcse:search>
    </div>
    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>