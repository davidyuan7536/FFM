var SELECTOR_GEO_TAGS;
var SELECTOR_ARTISTS;

$(document).ready(function() {
    $('.F-wrap').FormNavigate("Leaving the page will lost in unsaved data!");

    initFormElements();

    if ($('#Id').val() == '') {
        $('#Uploader, #Preview, #Delete').hide();
    } else {
        initSwfUploader('/i/decor/placeholder-article.png', 'event-upload.php?id=', 'event.php');
    }

    $('#Name').focus();

    if (!window.location.search.indexOf('?created=ok')) {
        $("#MessageText").text("Saved successfully");
        $("#Message").fadeIn('fast').delay(5000).fadeOut('slow');
    }
});


/**
 *
 */
function save() {
    if ($('#Name').val() == '') {
        $('#Name').addClass('F-Error');
        $('#Name').focus();
        return;
    }

    var artistsIds = [];
    $('.SA-item').each(function() {
        artistsIds.push($(this).attr('uid'));
    });

    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Name': $('#Name').val(),
        'Description': $('#Description').val(),
        'Address': $('#Address').val(),
        'GeoTagId': $('#GeoTagId').val(),
        'Date': $('#Date').val() + " " + $('#Time').val(),
        'ArtistsIds': artistsIds
    };

    $.post('event.php', data, function(result) {
        if (IsNumeric(result)) {
            $('#LoadingSecondary').show();
            window.location = 'event.php?created=ok&id=' + result;
        } else if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}

function deleteEvent() {
    $('#Delete').click(function() {
        if (confirm("Do you really want to delete this event?")) {
            var data = {
                'action': 'delete',
                'Id': $('#Id').val()
            };

            $.post('event.php', data, function(result) {
                if (result == 'OK') {
                    $("#MessageText").text("Event has been deleted");
                    $("#Message").fadeIn('fast');
                    cancel();
                } else {
                    $("#ErrorText").html(result);
                    $("#Error").fadeIn('fast');
                }
            });
        }
    });
}

function cancel() {
    window.location = '/site/admin/events.php'
}


/**
 *
 */
function initFormElements() {
    $("#Date").datepicker({
        showAnim: '', 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('#Name').change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });


    /**
     *
     */
    $('#GeoTagName, #GeoTagBrowse').click(function() {
        if (!SELECTOR_GEO_TAGS) {
            SELECTOR_GEO_TAGS = new SelectorGeoTags({
                callback: onSelectGeoTag
            });
        }
        SELECTOR_GEO_TAGS.open();
    });


    /**
     *
     */
    $('.SA-item').click(function() {
        global_formNavigate = false;
        $(this).remove();
    });
    $('.SG-wrap').disableTextSelect();
    $('.SA-button').click(function() {
        if (!SELECTOR_ARTISTS) {
            SELECTOR_ARTISTS = new SelectorArtists({
                callback: onSelectArtists
            });
        }
        var ids = [];
        $('.SA-item').each(function() {
            ids.push($(this).attr('uid'));
        });
        SELECTOR_ARTISTS.open(ids);
    });
}


/**
 *
 * @param geoTag
 */
function onSelectGeoTag(geoTag) {
    global_formNavigate = false;
    $('#GeoTagName').text(!geoTag ? 'None' : geoTag['name']);
    $('#GeoTagId').val(!geoTag ? '' : geoTag['geo_tag_id']);
}


/**
 *
 * @param artists
 */
function onSelectArtists(artists) {
    global_formNavigate = false;
    var c = $('#SA');
    $.each(artists, function(i, v) {
        $('<div/>', {
            'class': 'SA-item',
            'uid': v.uid,
            'html': '<img src="/site/i/icons/xfn.png" width="16" height="16" /> ' + $.escape(v.uname)
        }).click(function() {
            global_formNavigate = false;
            $(this).remove();
        }).appendTo(c);
    });
}
