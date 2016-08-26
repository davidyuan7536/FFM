var SELECTOR_ARTISTS;

$(document).ready(function() {
    $('.F-wrap').FormNavigate("Leaving the page will lost in unsaved data!");

    initFormElements();

    if ($('#Id').val() == '') {
        $('#Delete').hide();
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
        'ServiceName': $('#ServiceName').val(),
        'ServiceId': $('#ServiceId').val(),
        'ArtistId': artistsIds[0] || 0
    };

    $.post('video.php', data, function(result) {
        if (IsNumeric(result)) {
            $('#LoadingSecondary').show();
            window.location = 'video.php?created=ok&id=' + result;
        } else if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}

function deleteVideo() {
    $('#Delete').click(function() {
        if (confirm("Do you really want to delete this video?")) {
            var data = {
                'action': 'delete',
                'Id': $('#Id').val()
            };

            $.post('video.php', data, function(result) {
                if (result == 'OK') {
                    $("#MessageText").text("Video has been deleted");
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
    window.location = '/site/admin/videos.php'
}


/**
 *
 */
function initFormElements() {
    var services_regexp = {
        'vimeo': /vimeo\.com\/([0-9]*)[/\?]?/,
        'youtube': /youtube\.[a-z]{0,5}\/.*[\?&]v=([^&]*)/
    };
    
    $('#Url').change(function() {
        var s = $(this).val();
        if (s != '') {
            var m;
            if (s.indexOf('youtube') > -1) {
                m = services_regexp['youtube'].exec(s);
                if (m[1] && m[1].length > 1) {
                    $('#ServiceName').val('youtube');
                    $('#ServiceId').val(m[1]);
                }
            } else if (s.indexOf('vimeo') > -1) {
                m = services_regexp['vimeo'].exec(s);
                if (m[1] && m[1].length > 1) {
                    $('#ServiceName').val('vimeo');
                    $('#ServiceId').val(m[1]);
                }
            }
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
