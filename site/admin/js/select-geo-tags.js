var SelectableGeoTag = function(geoTag, index, callback) {
    var el = $('<div/>', {
        'class': 'S-item',
        'index': index,
        text: (geoTag['parent_id'] != '0' ? '- ' : '') + geoTag['name'],
        click: function() {
            callback($(this).attr('index'), false);
        },
        dblclick: function() {
            callback($(this).attr('index'), true);
        }
    });

    if (geoTag['parent_id'] == '0') {
        el.css({'font-weight': 'bold'});
    }

    el.disableTextSelect();

    return {
        getElement: function() {
            return el;
        },
        getTag: function() {
            return geoTag;
        }
    }
};

var SelectorGeoTags = function(o) {
    var tagList = [];
    var selectedTag;
    var options = {
        callback: null
    };

    $.extend(options, o);

    var dialog = $('<div/>', {
        title: 'Select geotag'
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

    var list = $('<div/>', {
        'class': 'S-wrap'
    }).appendTo(dialog);

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
        options.callback(!selectedTag ? null : selectedTag.getTag());
        close();
    }

    function selectNone() {
        options.callback(null);
        close();
    }

    function close() {
        dialog.dialog('close');
    }

    function open() {
        selectedTag = null;
        list.empty().text('Loading...');

        $.post('geotags.php', {'action': 'list'}, function(result) {
            tagList = [];
            list.empty();
            for (var i in result) {
                var tg = new SelectableGeoTag(result[i], tagList.length, onSelectTag);
                tg.getElement().appendTo(list);
                tagList.push(tg);
                for (var j in result[i]['childNodes']) {
                    var stg = new SelectableGeoTag(result[i]['childNodes'][j], tagList.length, onSelectTag);
                    stg.getElement().appendTo(list);
                    tagList.push(stg);
                }
            }
        }, 'json');

        dialog.dialog('open');
    }

    function onSelectTag(index, saveAndClose) {
        if (selectedTag) {
            selectedTag.getElement().removeClass('ui-selected');
        }
        selectedTag = tagList[index];
        selectedTag.getElement().addClass('ui-selected');
        if (saveAndClose) {
            saveSelected();
        }
    }

    return {
        open: open
    }
};

