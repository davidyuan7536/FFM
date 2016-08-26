var SELECTOR_GEO_TAGS;
var SELECTOR_GENRES;
var SELECTOR_ARTISTS;

$(document).ready(function() {
    $('.F-wrap').FormNavigate("Leaving the page will lost in unsaved data!");

    initFormElements();

    if ($('#Id').val() == '') {
        $('#Uploader, #Preview, #Delete').hide();
    } else {
        initSwfUploader('/i/decor/placeholder-article.png', 'article-upload.php?id=', 'article-summary.php');
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

    var genresIds = [];
    $('.SG-item').each(function() {
        genresIds.push($(this).attr('uid'));
    });

    var artistsIds = [];
    $('.SA-item').each(function() {
        artistsIds.push($(this).attr('uid'));
    });

    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Filename': $('#Filename').val(),
        'Name': $('#Name').val(),
        'Description': $('#Description').val(),
        'NameRu': $('#NameRu').val(),
        'DescriptionRu': $('#DescriptionRu').val(),
        'GeoTagId': $('#GeoTagId').val(),
        'GenresIds': genresIds,
        'ArtistsIds': artistsIds,
        'Status': $('#Status:checked').val() === undefined ? 'draft' : 'publish'
    };

    $.post('article-summary.php', data, function(result) {
        if (IsNumeric(result)) {
            $('#LoadingSecondary').show();
            window.location = 'article-summary.php?created=ok&id=' + result;
        } else if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}

function deleteArticle() {
    $('#Delete').click(function() {
        if (confirm("Do you really want to delete this article?")) {
            var data = {
                'action': 'delete',
                'Id': $('#Id').val()
            };

            $.post('article-summary.php', data, function(result) {
                if (result == 'OK') {
                    $("#MessageText").text("Article has been deleted");
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
    window.location = '/site/admin/articles.php'
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
