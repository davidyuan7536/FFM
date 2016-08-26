var SelectableGenre = function(genre, selectable) {
    var el = $('<div/>', {
        'class': 'S-item ' + (selectable ? 'S-s' : 'S-n'),
        'uid': genre['genre_id'],
        'uname': genre['name'],
        text: (genre['parent_id'] != '0' ? '- ' : '') + genre['name']
    });

    if (genre['parent_id'] == '0') {
        el.css({'font-weight': 'bold'});
    }

    return {
        getElement: function() {
            return el;
        }
    }
};

var SelectorGenres = function(o) {
    var tagList = [];
    var selectedTag;
    var options = {
        callback: null
    };

    $.extend(options, o);

    var dialog = $('<div/>', {
        title: 'Select genres'
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

    var wrap = $('<div/>', {
        'class': 'S-wrap S-genres'
    }).appendTo(dialog);

    wrap.disableTextSelect();

    var list = $('<div/>').appendTo(wrap);

    var buttons = $('<div/>').css({'padding-top': '16px'}).appendTo(dialog);

    $('<button/>', {
        'class': 'Button Small blue',
        css: {'float': 'right'},
        tabindex: -1,
        text: 'Select None',
        click: selectNone
    }).appendTo(buttons);

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
        $('.S-genres .ui-selected').each(function() {
            ids.push({
                'uid': $(this).attr('uid'),
                'uname': $(this).attr('uname')
            });
        });
        options.callback(ids);
        close();
    }

    function selectNone() {
        options.callback([]);
        close();
    }

    function close() {
        dialog.dialog('close');
    }

    function open(ids) {
        selectedTag = null;
        list.empty().text('Loading...');

        $.post('genres.php', {'action': 'list'}, function(result) {
            tagList = [];
            list.empty();
            for (var i in result) {
                var tg = new SelectableGenre(result[i], $.inArray(result[i]['genre_id'], ids) == -1);
                tg.getElement().appendTo(list);
                tagList.push(tg);
                for (var j in result[i]['childNodes']) {
                    var stg = new SelectableGenre(result[i]['childNodes'][j], $.inArray(result[i]['childNodes'][j]['genre_id'], ids) == -1);
                    stg.getElement().appendTo(list);
                    tagList.push(stg);
                }
            }
            list.selectable({filter: '.S-s'});
        }, 'json');

        dialog.dialog('open');
    }

    return {
        open: open
    }
};

