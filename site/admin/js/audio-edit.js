var SELECTOR_ARTISTS;

$(document).ready(function() {
    $('.F-wrap').FormNavigate("Leaving the page will lost in unsaved data!");

    initFormElements();

    $('#Name').focus();
});


/**
 *
 */
function loadInfo() {
    var data = {
        'action': 'info',
        'Id': $('#Id').val()
    };

    $('#NameHelper').slideUp();
    $('#AlbumHelper').slideUp();
    $('#ArtistHelper').slideUp();

    $.post('audio-edit.php', data, function(tags) {
        var i;

        if (!tags) {
            return false;
        }

        if (tags.title) {
            $('#NameHelper').empty();
            for (i = 0; i < tags.title.length; i++) {
                $('<span/>', {
                    'class': 'Link',
                    'value': tags.title[i],
                    text: tags.title[i]
                }).click(function() {
                    $('#Name').val($(this).attr('value'));
                }).appendTo('#NameHelper');
                if (i + 1 < tags.title.length) {
                    $('<span/>').text(', ').appendTo('#NameHelper');
                }
            }
            $('#NameHelper').slideDown();
        }

        if (tags.album) {
            $('#AlbumHelper').empty();
            for (i = 0; i < tags.album.length; i++) {
                $('<span/>', {
                    'class': 'Link',
                    'value': tags.album[i],
                    text: tags.album[i]
                }).click(function() {
                    $('#AudioAlbum').val($(this).attr('value'));
                }).appendTo('#AlbumHelper');
                if (i + 1 < tags.album.length) {
                    $('<span/>').text(', ').appendTo('#AlbumHelper');
                }
            }
            $('#AlbumHelper').slideDown();
        }

        if (tags.artist) {
            $('#ArtistHelper').empty();
            for (i = 0; i < tags.artist.length; i++) {
                $('<span/>', {
                    'value': tags.artist[i],
                    text: tags.artist[i]
                }).appendTo('#ArtistHelper');
                if (i + 1 < tags.artist.length) {
                    $('<span/>').text(', ').appendTo('#ArtistHelper');
                }
            }
            $('#ArtistHelper').slideDown();
        }
    }, 'json');
}

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
        'AudioAlbum': $('#AudioAlbum').val(),
        'ArtistId': artistsIds[0] || 0
    };

    $.post('audio-edit.php', data, function(result) {
        if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}

function deleteAudio() {
    $('#Delete').click(function() {
        if (confirm("Do you really want to delete this MP3 file?")) {
            var data = {
                'action': 'delete',
                'Id': $('#Id').val()
            };

            $.post('audio-edit.php', data, function(result) {
                if (result == 'OK') {
                    $("#MessageText").text("MP3 file has been deleted");
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
    window.location = '/site/admin/audio.php'
}


/**
 *
 */
function initFormElements() {
    $('#Wand').click(loadInfo);

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
    $('.SA-item').click(function() {
        global_formNavigate = false;
        $(this).remove();
    });
    $('.SG-wrap').disableTextSelect();
    $('.SA-button').click(function() {
        if (!SELECTOR_ARTISTS) {
            SELECTOR_ARTISTS = new SelectorArtists({
                multiselect: false,
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
 * @param artists
 */
function onSelectArtists(artists) {
    global_formNavigate = false;
    var c = $('#SA');
    if (artists.length > 0) {
        var v = artists[0];
        c.empty();
        $('<div/>', {
            'class': 'SA-item',
            'uid': v.uid,
            'html': '<img src="/site/i/icons/xfn.png" width="16" height="16" /> ' + $.escape(v.uname)
        }).click(function() {
            global_formNavigate = false;
            $(this).remove();
        }).appendTo(c);
    }
}
