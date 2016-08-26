<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
<style type="text/css">{literal}
.Video-label {
    font-size: 15px;
    font-weight: bold;
    text-align: right;
    display: block;
    padding: 8px 8px 8px 0;
}

.Video-wrap {
    background-color: #f5f6f8;
    padding: 12px 16px 6px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.Video-text,
.Video-input {
    background-color: #fff;
    border: 1px solid #cbd0d6;
    padding: 3px 0;
    font: 15px Helvetica, Arial, sans-serif;
}

.Video-input {
    width: 175px;
}
{/literal}</style>
<script type="text/javascript">{literal}
var MANDATORY_FIELDS = '#Name, #Email, #Subject';

function SendMessage() {
    if (onSubmitCheck()) {
        var data = {
            'Action': 'video',
            'Name': $('#Name').val(),
            'Email': $('#Email').val(),
            'Subject': $('#Subject').val(),
            'Text': $('#Text').val()
        };

        $.post('/about/', data, function(result) {
            if (result == 'OK') {
                $("#Message").text("Your message has been sent.");
                $("#Message").fadeIn('fast').delay(5000).fadeOut('slow');
            } else {
                $("#Message").html(result);
                $("#Message").fadeIn('fast');
            }
        });
    }
}

function onSubmitCheck() {
    var result = true;
    $(MANDATORY_FIELDS).each(function () {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
            $(this).focus();
            return result = false;
        }
    });
    return result;
}

$(document).ready(function() {
    $(MANDATORY_FIELDS).change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
        } else {
            $(this).removeClass('F-error');
        }
    });
    $('#Submit').click(SendMessage);
});
{/literal}</script>
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    {if $CurrentPage == 1}
        <div style="padding-bottom: 16px;">
            <div class="C-left">
        <h1>{$Title}</h1>
                <div class="V-frame-big-wrap"><div class="V-frame-big">
                    <div>
                        {if $Videos[0].service_name == 'youtube'}
                            <iframe src="http://www.youtube.com/embed/{$Videos[0].service_id}" width="580" height="325" frameborder="0"></iframe>
                        {elseif $Videos[0].service_name == 'vimeo'}
                            <iframe src="http://player.vimeo.com/video/{$Videos[0].service_id}?portrait=0&byline=0&title=0&color=ec0009" width="580" height="325" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"></iframe>
                        {/if}
                    </div>
                </div></div>
            </div>
            <div class="C-right" style="width: 300px">
                <h2 style="text-transform: none;">{$LANG.headers.submitVideo}</h2>

                <div class="Video-wrap">
                    <table style="width: 100%;">
                    <colgroup>
                        <col style="width: 87px;" />
                    </colgroup>
                    <tr>
                        <td><label for="Name" class="Video-label">{$LANG.form.name}<span class="F-mandatory">*</span></label></td>
                        <td><input type="text" id="Name" class="Video-input"></td>
                    </tr>
                    <tr>
                        <td><label for="Email" class="Video-label">{$LANG.form.email}<span class="F-mandatory">*</span></label></td>
                        <td><input type="text" id="Email" class="Video-input"></td>
                    </tr>
                    <tr>
                        <td><label for="Subject" class="Video-label">{$LANG.form.link}<span class="F-mandatory">*</span></label></td>
                        <td><input type="text" id="Subject" class="Video-input"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label for="Text" class="Video-label" style="text-align: left;">{$LANG.form.comment}</label></td>
                    </tr>
                    </table>
                    <div style="padding: 0 0 8px;">
                        <textarea id="Text" cols="30" rows="10" class="Video-text" style="width: 258px; height: 140px;"></textarea>
                    </div>
                    <div style="text-align: right; padding-bottom: 6px;">
                        <input type="button" id="Submit" value="{$LANG.form.submit}" class="F-button"/>
                    </div>
                    <div id="Message" class="F-message" style="display: none;"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    {else}
        <h1><a href="/video/">{$Title}</a></h1>
    {/if}


    <div class="V-container-wrap"><div class="V-container-inner">

        <div class="V-row V-row-first">
        {section name=row loop=$Videos}
            <div class="V-wrap{if $smarty.section.row.iteration % 3 == 0} V-last{/if}">
                <div class="V-name">{if $Videos[row].artist_id != 0}<a href="/artists/{$Videos[row].artist.filename}.html">{$Videos[row].artist.name|escape}</a>
                    &ndash; {/if}<span title="{$Videos[row].video_name}">{$Videos[row].video_name}</span></div>
                <div class="V-frame">
                    {if $Videos[row].service_name == 'youtube'}
                        <iframe src="http://www.youtube.com/embed/{$Videos[row].service_id}" width="300" height="170" frameborder="0"></iframe>
                    {elseif $Videos[row].service_name == 'vimeo'}
                        <iframe src="http://player.vimeo.com/video/{$Videos[row].service_id}?portrait=0&byline=0&title=0&color=ec0009" width="300" height="170" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"></iframe>
                    {/if}
                </div>
            </div>
            {if $smarty.section.row.iteration % 3 == 0 && !$smarty.section.row.last}
                <div class="clear"></div>
                </div><div class="V-row">
            {/if}
        {/section}
                <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div></div>


    <div class="P-wrap">
        {include file='includes/widget-pages-links.tpl'}
    </div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>