var SELECTOR_GEO_TAGS;
var SELECTOR_GENRES;

$(document).ready(function() {
    $('.F-wrap').FormNavigate("Leaving the page will lost in unsaved data!");

    initFormElements();

    if ($('#Id').val() == '') {
        $('#Uploader, #Preview, #Delete').hide();
    } else {
        initSwfUploader('/i/decor/placeholder-artist.png', 'artist-upload.php?id=', 'artist.php');
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

    var ids = [];
    $('.SG-item').each(function() {
        ids.push($(this).attr('uid'));
    });

    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Filename': $('#Filename').val(),
        'Name': $('#Name').val(),
        'NameRu': $('#NameRu').val(),
        'Description': $('#Description').val(),
        'Links': $('#Links').val(),
        'GeoTagId': $('#GeoTagId').val(),
        'GenresIds': ids
    };

    $.post('artist.php', data, function(result) {
        if (IsNumeric(result)) {
            $('#LoadingSecondary').show();
            window.location = 'artist.php?created=ok&id=' + result;
        } else if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}

function deleteArtist() {
    $('#Delete').click(function() {
        if (confirm("ATTENTION! Do you really want to delete this artist?\rDiscography and MP3-files will also be DELETED!")) {
            var data = {
                'action': 'delete',
                'Id': $('#Id').val()
            };

            $.post('artist.php', data, function(result) {
                if (result == 'OK') {
                    $("#MessageText").text("Artist has been deleted");
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
    window.location = '/site/admin/artists.php';
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
 * @param genres
 */
function onSelectGenres(genres) {
    global_formNavigate = false;
    var c = $('#SG');
    $.each(genres, function(i, v) {
        $('<div/>', {
            'class': 'SG-item',
            'uid': v.uid,
            'text': v.uname
        }).click(function() {
            global_formNavigate = false;
            $(this).remove();
        }).appendTo(c);
    });
}

/**
 *
 */
function initFormElements() {
    $('#Filename').dblclick(function() {
        if ($(this).attr('readonly') && confirm("Do you really want to edit this field?")) {
            $(this).removeAttr('readonly');
        }
    });

    $('#Name').change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });

    $('#GeoTagName, #GeoTagBrowse').click(function() {
        if (!SELECTOR_GEO_TAGS) {
            SELECTOR_GEO_TAGS = new SelectorGeoTags({
                callback: onSelectGeoTag
            });
        }
        SELECTOR_GEO_TAGS.open();
    });

    $('.SG-item').click(function() {
        global_formNavigate = false;
        $(this).remove();
    });
    $('.SG-wrap').disableTextSelect();
    $('.SG-button').click(function() {
        if (!SELECTOR_GENRES) {
            SELECTOR_GENRES = new SelectorGenres({
                callback: onSelectGenres
            });
        }
        var ids = [];
        $('.SG-item').each(function() {
            ids.push($(this).attr('uid'));
        });
        SELECTOR_GENRES.open(ids);
    });
}
