<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
<style type="text/css">{literal}
.About p {
    padding: 0 0 1.3em;
    line-height: 20px;
}

.Contact-wrap {
    padding: 12px 0 0;
    width: 280px;
}

.Contact-label {
    font-size: 15px;
    font-weight: bold;
    text-align: right;
    display: block;
    padding: 8px 8px 8px 0;
}

.Contact-text,
.Contact-input {
    background-color: #f5f6f8;
    border: 1px solid #cbd0d6;
    padding: 3px 0;
    font: 15px Helvetica, Arial, sans-serif;
}

.Contact-input {
    width: 190px;    
}
{/literal}</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">{literal}
var MANDATORY_FIELDS = '#Name, #Email, #Text';

function SendMessage() {
    if (onSubmitCheck()) {
        var data = {
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


    var regions = [];
    var index = 0;

    $('#YMapsID span').each(function() {
        regions.push([$(this).attr('title'), $(this).attr('lat'), $(this).attr('lng'), index, '<a href="/artists/?region=' + $(this).attr('region') + '" target="_blank">' + $(this).attr('description') + '</a>']);
        index += 1;
    });

    var options = {
        zoom: 3,
        center: new google.maps.LatLng(57, 70),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        navigationControl: true,
        navigationControlOptions: {
            style: google.maps.NavigationControlStyle.SMALL,
            position: google.maps.ControlPosition.TOP_RIGHT
        }
    };
    var map = new google.maps.Map(document.getElementById("YMapsID"), options);

    setMarkers(map, regions);

    function setMarkers(map, locations) {
        var image = new google.maps.MarkerImage('/i/icons/marker.png',
                new google.maps.Size(27, 26),
                new google.maps.Point(0, 0),
                new google.maps.Point(8, 26)
            );
        for (var i = 0; i < locations.length; i++) {
            var region = locations[i];
            var myLatLng = new google.maps.LatLng(region[1], region[2]);
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: image,
                title: region[0],
                zIndex: region[3]
            });
            attachMessage(map, marker, region[4]);
        }
    }

    var iw;

    function attachMessage(map, marker, message) {
        var infowindow = new google.maps.InfoWindow({
            content: message
        });
        google.maps.event.addListener(marker, 'click', function() {
            if (iw) {
                iw.close();
            }
            infowindow.open(map, marker);
            iw = infowindow;
        });
    }
});
{/literal}</script>
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    <div class="C-left About">
        <div style="width: 280px; float: left;"><img src="/i/david_macfadyen.jpg?{$V}" alt="" width="230" height="500" /></div>

        <div style="float: left; padding-top: 12px;">
            {*<h1 class="H-article">
                {if $LANG.id == 'ru' && $About.about_header_ru}{$About.about_header_ru|escape|nl2br}{else}{$About.about_header|escape|nl2br}{/if}
            </h1>*}
            <div style="width: 330px; text-align: justify;">
                {if $LANG.id == 'ru' && $About.about_ru}{$About.about_ru}{else}{$About.about}{/if}
            </div>
        </div>
    </div>

    <div class="C-right">
        <div style="float: right;"><a href="http://www.ucla.edu/" target="_blank"><img src="/i/decor/ucla.png" alt="" width="91" height="30" /></a></div>
        <div class="clear"></div>
        
        <h2 style="font-size: 24px; text-transform: none; padding-top: 100px;">{$LANG.headers.contactUs}</h2>

        <div class="Contact-wrap">
            <div style="border-left: 1px solid #cbd0d6; padding-left: 36px; margin-left: -36px;">
                <table style="width: 100%; ">
                <colgroup>
                    <col style="width: 87px;" />
                </colgroup>
                <tr>
                    <td><label for="Name" class="Contact-label">{$LANG.form.name}<span class="F-mandatory">*</span></label></td>
                    <td><input type="text" id="Name" class="Contact-input"/></td>
                </tr>
                <tr>
                    <td><label for="Email" class="Contact-label">{$LANG.form.email}<span class="F-mandatory">*</span></label></td>
                    <td><input type="text" id="Email" class="Contact-input"/></td>
                </tr>
                <tr>
                    <td><label for="Subject" class="Contact-label">{$LANG.form.subject}</label></td>
                    <td><input type="text" id="Subject" class="Contact-input"/></td>
                </tr>
                </table>
                <div style="padding: 4px 0 0;">
                    <textarea id="Text" cols="30" rows="10" class="Contact-text" style="width: 273px; height: 120px;"></textarea>
                </div>
            </div>
            <div style="text-align: right; padding: 8px 0 10px;">
                <input type="button" id="Submit" value="{$LANG.form.send}" class="F-button"/>
            </div>
            <div id="Message" class="F-message" style="display: none;"></div>
        </div>
    </div>

    <div class="clear"></div>

    <div style="padding: 32px 0 16px;">
        <div id="YMapsID" style="height:400px;background: #eee;">
            {foreach from=$GeoTags item=GeoTag}
                <span lat="{$GeoTag.lat}" lng="{$GeoTag.lng}" title="{$GeoTag.name}" region="{$GeoTag.filename}" description="{$GeoTag.longname}"></span>
            {/foreach}
        </div>
    </div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>