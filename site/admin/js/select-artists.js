var SelectableArtist = function(artist, selectable) {
    var el = $('<div/>', {
        'class': 'S-item ' + (selectable ? 'S-s' : 'S-n'),
        'uid': artist['artist_id'],
        'uname': artist['name'],
        'html': $.escape(artist['name'])
    });

    return {
        getElement: function() {
            return el;
        }
    }
};

var SelectorArtists = function(o) {
    var sIds;
    var tagList = [];
    var selectedTag;
    var options = {
        multiselect: true,
        callback: null
    };

    $.extend(options, o);

    var dialog = $('<div/>', {
        title: 'Select Artists'
    });
    dialog.dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        position: 'center',
        open: function(event, ui) {
            $('body').css({'overflow': 'hidden'});
        },
        close: function(event, ui) {
            $('body').css({'overflow': 'auto'});
        }
    });

    var search = $('<input type="search" placeholder="search" results="8" accesskey="s" style="width: 100%"/>').keypress(function(event) {
        if (event.keyCode == '13') {
            event.preventDefault();
            reload();
        }
    }).appendTo(dialog);

    var wrap = $('<div/>', {
        'class': 'S-wrap S-artists'
    }).appendTo(dialog);

    wrap.disableTextSelect();

    var list = $('<div/>').appendTo(wrap);

    var buttons = $('<div/>').css({'padding-top': '16px'}).appendTo(dialog);

    $('<button/>', {
        'class': 'Button blue F-button',
        text: 'Select',
        click: saveSelected
    }).appendTo(buttons);

    $('<span/>').text(' ').appendTo(buttons);

    $('<button/>', {
        'class': 'Button gray F-button',
        text: 'Cancel',
        click: close
    }).appendTo(buttons);

    function saveSelected() {
        var ids = [];
        $('.S-artists .ui-selected').each(function() {
            ids.push({
                'uid': $(this).attr('uid'),
                'uname': $(this).attr('uname')
            });
        });
        options.callback(ids);
        close();
    }

    function close() {
        dialog.dialog('close');
    }

    function reload() {
        $.post('artists.php', {'action': 'list', 'search': search.val()}, function(result) {
            tagList = [];
            list.empty();
            for (var i in result) {
                var tg = new SelectableArtist(result[i], $.inArray(result[i]['artist_id'], sIds) == -1);
                tg.getElement().appendTo(list);
                tagList.push(tg);
            }
            if (options.multiselect) {
                list.selectable({filter: '.S-s'});
            } else {
                list.selectableOne({filter: '.S-s'});
            }
        }, 'json');
    }

    function open(ids) {
        sIds = ids;
        selectedTag = null;
        list.empty();
        reload();
        dialog.dialog('open');
        search.focus();
    }

    return {
        open: open
    }
};

