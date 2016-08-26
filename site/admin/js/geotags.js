var GEO_CODER;
var MAP;
var MARKER;

var FIELDS = '#Name, #Fullname, #Wikilink, #Lat, #Lng, #Zoom';


$(document).ready(function() {
    $('#Dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 600,
        position: 'center',
        open: function(event, ui) {
            $('body').css({'overflow': 'hidden'});
        },
        close: function(event, ui) {
            $('body').css({'overflow': 'auto'});
        }
    });

    initFormElements();
    initGeoCoder();

    reload();
});


/**
 * DIALOG
 */

function openDialog(geoTag) {
    $('#Id').val('');
    $('#Filename').val('').attr('readonly', 'readonly');

    if (geoTag == null) {
        $(FIELDS).val('').removeClass('F-Error');
        $('#Delete').hide();
    } else {
        $(FIELDS).removeClass('F-Error');

        $('#Id').val(geoTag['geo_tag_id']);
        $('#Name').val(geoTag['name']);
        $('#Fullname').val(geoTag['longname']);
        $('#Filename').val(geoTag['filename']);
        $('#Wikilink').val(geoTag['wiki']);
        $('#Lat').val(geoTag['lat']);
        $('#Lng').val(geoTag['lng']);
        $('#Zoom').val(geoTag['zoom']);

        $('#Delete').show();
    }

    $('#Dialog').dialog('open');
    $('#Name').focus();

    initMap();
    if (geoTag != null) {
        onMarkerClick();
    }
}

function closeDialog() {
    $('#Dialog').dialog('close');
}


/**
 * ACTIONS
 */

function move(sourceId, targetId) {
    var data = {
        'action': 'move',
        'sourceId': sourceId,
        'targetId': targetId
    };

    $.post('geotags.php', data, function(result) {
        if (result == 'OK') {
            reload();
        }
    });
}

function reload() {
    $.post('geotags.php', {'action': 'list'}, function(result) {
        $('#List').empty();

        var list = new GeoTagList(result);
        list.redraw('#List');

        $(".G-nonexpandable, .G-item").draggable({
            revert: 'invalid',
            scrollSensitivity: 100,
            opacity: 0.7,
            helper: 'clone'
        });

        $("#Space").droppable({
            accept: '.G-item',
            activeClass: 'ui-state-hover',
            hoverClass: 'ui-state-active',
            drop: function(event, ui) {
                move(ui.draggable.attr('UID'), 0);
            }
        });

        $(".G-expandable, .G-nonexpandable").droppable({
            accept: '.G-item, .G-nonexpandable',
            hoverClass: 'ui-state-active',
            drop: function(event, ui) {
                move(ui.draggable.attr('UID'), $(this).attr('UID'));
            }
        });

    }, 'json');
}

function save() {
    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Name': $('#Name').val(),
        'Filename': $('#Filename').val(),
        'Fullname': $('#Fullname').val(),
        'Wikilink': $('#Wikilink').val(),
        'Lat': $('#Lat').val(),
        'Lng': $('#Lng').val(),
        'Zoom': $('#Zoom').val()
    };

    $.post('geotags.php', data, function(result) {
        if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
            reload();
            closeDialog();
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}

function deleteTag() {
    var data = {
        'action': 'delete',
        'Id': $('#Id').val()
    };

    $.post('geotags.php', data, function(result) {
        if (result == 'OK') {
            $("#MessageText").text("Geotag has been deleted");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
            reload();
            closeDialog();
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}


/**
 * FORM
 */

function initFormElements() {
    fieldsEmptyCheck(FIELDS);

    $('#Filename').dblclick(function() {
        if ($(this).attr('readonly') && confirm("Do you really want to edit this field?")) {
            $(this).removeAttr('readonly');
        }
    });

    $('#Add').click(function() {
        openDialog(null);
    });

    $('#Delete').click(function() {
        if (confirm("Do you really want to delete this geotag?")) {
            deleteTag();
        }
    });

    $('#Save').click(function() {
        if (onSubmitCheck()) {
            save();
        }
    });
    
    $('#Cancel').click(closeDialog);
    $('#Marker').click(onMarkerClick);
}

function fieldsEmptyCheck(fields) {
    $(fields).change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });
}

function onSubmitCheck() {
    var result = true;
    $(FIELDS).each(function () {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
            $(this).focus();
            return result = false;
        }
    });
    return result;
}


/**
 * MAP
 */

function initGeoCoder() {
    GEO_CODER = new google.maps.Geocoder();
    $('#Quick').keypress(function(event) {
        if (event.keyCode == '13') {
            event.preventDefault();

            var address = $('#Quick').val();
            if (GEO_CODER) {
                GEO_CODER.geocode({ 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        MAP.setCenter(results[0].geometry.location);
                        MAP.setZoom(11);
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }
        }
    });
}

function initMap() {
    MARKER = null;
    var latlng = new google.maps.LatLng(54, 32);
    var myOptions = {
        zoom: 5,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    MAP = new google.maps.Map(document.getElementById("Map"), myOptions);
    google.maps.event.addListener(MAP, 'click', function(event) {
        setMarker(event.latLng);
        $("#Lat").val(event.latLng.lat());
        $("#Lng").val(event.latLng.lng());
        $("#Zoom").val(MAP.getZoom());
    });
}

function setMarker(position) {
    if (!MARKER) {
        MARKER = new google.maps.Marker({
            position: position,
            map: MAP
        });
    } else {
        MARKER.setPosition(position);
    }
}

function onMarkerClick() {
    var location = new google.maps.LatLng($("#Lat").val(), $("#Lng").val());
    setMarker(location);
    MAP.setCenter(location);
    MAP.setZoom(parseInt($("#Zoom").val(), 10));
}


/**
 * LIST
 */

var GeoTag = function(geoTag) {
    var PLUS = '/site/i/icons/toggle-small-expand.png';
    var MINUS = '/site/i/icons/toggle-small.png';
    var LEAF = '/site/i/icons/toggle-node.png';

    var el = $('<div/>', {'class': 'G-wrap', 'UID': geoTag['geo_tag_id']});
    var sc = new StateCookie('CP_GEO_TAGS');
    var hasChildren = geoTag['childNodes'] != '';
    var collapsed = sc.getState(geoTag['geo_tag_id']);

    el.disableTextSelect();

    var row = $('<div/>', {'class': 'G-head'}).appendTo(el);

    var img = $('<img/>', {
        src: hasChildren ? collapsed ? PLUS : MINUS : LEAF,
        width: 16,
        height: 16
    }).appendTo(row);
    $('<span/>').text(geoTag['name']).click(function() {
        openDialog(geoTag);
    }).appendTo(row);

    if (!hasChildren) {
        el.addClass('G-nonexpandable');
    } else {
        el.addClass('G-expandable');
        img.click(function () {
            if (list.css('display') == 'none') {
                img.attr('src', MINUS);
                list.slideDown('fast');
                sc.setState(geoTag['geo_tag_id'], false);
            } else {
                img.attr('src', PLUS);
                list.slideUp('fast');
                sc.setState(geoTag['geo_tag_id'], true);
            }
        });

        var list = $('<div/>', {'class': 'G-list'}).css({'display': collapsed ? 'none' : 'block'}).appendTo(el);

        for (var i in geoTag['childNodes']) {
            var d = $('<div/>', {
                'class': 'G-item',
                'UID': geoTag['childNodes'][i]['geo_tag_id']
            }).appendTo(list);

            $('<span/>', {
                'text': geoTag['childNodes'][i]['name'],
                'index': i
            }).click(function() {
                openDialog(geoTag['childNodes'][$(this).attr('index')]);
            }).appendTo(d);
        }
    }

    return {
        getElement: function() {
            return el;
        }
    }
};

var GeoTagList = function(t) {

    function redraw(el) {
        var c = $(el);
        for (var i in t) {
            var g = new GeoTag(t[i]);
            g.getElement().appendTo(c);
        }
    }

    return {
        redraw: redraw
    }
};


