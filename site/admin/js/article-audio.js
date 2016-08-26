$(document).ready(function() {
    reloadPlaylist();

    $('#Search').keyup(searchTracks).change(searchTracks);
});

var search = '';
var searching = false;
var toSearch = false;

function searchTracks() {
    if ($('#Search').val() != search) {
        if (!searching) {
            search = $('#Search').val();
            
            var data = {
                'action': 'search',
                'search': $('#Search').val()
            };

            $.post('article-audio.php', data, function(result) {
                var track;

                $('#Suggest').empty();

                $.each(result, function(i, v) {
                    track = new SuggestedTrack(v);
                    track.getElement().appendTo('#Suggest');
                });

                searching = false;

                if (toSearch) {
                    toSearch = false;
                    searchTracks();
                }
            }, 'json');

            searching = true;
        } else {
            toSearch = true;
        }
    }
}

function reloadPlaylist() {
    var data = {
        'action': 'list',
        'article_id': $('#Id').val()
    };

    $.post('article-audio.php', data, function(result) {
        var track;

        $('#List').empty();

        $.each(result, function(i, v) {
            track = new Track(v);
            track.getElement().appendTo('#List');
        });

    }, 'json');
}

function addTrack(audio_id) {
    var data = {
        'action': 'add',
        'article_id': $('#Id').val(),
        'audio_id': audio_id
    };

    $.post('article-audio.php', data, function(result) {
        reloadPlaylist();
    }, 'json');
}

function removeTrack(audio_id) {
    var data = {
        'action': 'remove',
        'article_id': $('#Id').val(),
        'audio_id': audio_id
    };

    $.post('article-audio.php', data, function(result) {
        reloadPlaylist();
    }, 'json');
}

var Track = function(data) {
    var el = $('<div/>', {
        'class': 'File-wrap'
    });

    $('<a/>', {
        href: '/site/admin/audio-edit.php?id=' + data['audio_id'],
        text: data['audio_filename'],
        target: '_blank'
    }).appendTo(el);

    $('<span/>', {
        text: ' '
    }).appendTo(el);

    $('<img/>', {
        src: '/site/i/icons/cross-small.png',
        width: 16,
        height: 16,
        title: 'Remove track from playlist',
        css: {'vertical-align': '-2px', 'cursor': 'pointer'}
    }).click(function() {
        removeTrack(data['audio_id']);
        el.slideUp();
    }).appendTo(el);

    return {
        getElement: function() {
            return el;
        }
    }
};

var SuggestedTrack = function(data) {
    var el = $('<div/>', {
        'class': 'File-suggested',
        title: data['audio_name']
    });

    $('<span/>', {
        text: data['audio_filename']
    }).click(function() {
        addTrack(data['audio_id']);
        el.fadeOut();
    }).appendTo(el);

    $('<span/>', {
        text: ' '
    }).appendTo(el);

    return {
        getElement: function() {
            return el;
        }
    }
};
