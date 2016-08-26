$(document).ready(function() {
    $('#dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 150,
        position: 'center',
        open: function(event, ui) {
            $('body').css({'overflow': 'hidden'});
        },
        close: function(event, ui) {
            $('body').css({'overflow': 'auto'});
        }
    });

    initFormElements();

    reload();
});


/**
 * DIALOG
 */

function openDialog(genre) {
    if (genre == null) {
        $('#Filename').val('').attr('readonly', 'readonly');
        $('#Id').val('');
        $('#Name').val('').removeClass('F-Error');
        $('#Delete').hide();
    } else {
        $('#Filename').val(genre['filename']).attr('readonly', 'readonly');
        $('#Id').val(genre['genre_id']);
        $('#Name').val(genre['name']).removeClass('F-Error');
        $('#Delete').show();
    }
    $('#dialog').dialog('open');
    $('#Name').focus();
}

function closeDialog() {
    $('#dialog').dialog('close');
}


/**
 * FORM
 */

function initFormElements() {
    $('#Filename').dblclick(function() {
        if ($(this).attr('readonly') && confirm("Do you really want to edit this field?")) {
            $(this).removeAttr('readonly');
        }
    });

    $('#Name').change(function() {
        if($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });

    $('#Add').click(function() {
        openDialog(null);
    });

    $('#Delete').click(function() {
        if (confirm("Do you really want to delete this genre?")) {
            deleteGenre();
        }
    });

    $('#Save').click(save);
    $('#Cancel').click(closeDialog);
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

    $.post('genres.php', data, function(result) {
        if(result == 'OK') {
            reload();
        }
    });
}

function reload() {
    $.post('genres.php', {'action': 'list'}, function(result) {
        $('#List').empty();

        var genres = new Genres(result);
        genres.redraw('#List');

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
			    move(ui.draggable.attr('genreId'), 0);
			}
		});

		$(".G-expandable, .G-nonexpandable").droppable({
			accept: '.G-item, .G-nonexpandable',
			hoverClass: 'ui-state-active',
			drop: function(event, ui) {
			    move(ui.draggable.attr('genreId'), $(this).attr('genreId'));
			}
		});

//        $('.G-item').disableSelection();
    }, 'json');
}

function save() {
    if ($('#Name').val() == '') {
        $('#Name').addClass('F-Error');
        $('#Name').focus();
        return;
    }
    
    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Name': $('#Name').val(),
        'Filename': $('#Filename').val()
    };

    $.post('genres.php', data, function(result) {
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

function deleteGenre() {
    var data = {
        'action': 'delete',
        'Id': $('#Id').val()
    };

    $.post('genres.php', data, function(result) {
        if (result == 'OK') {
            $("#MessageText").text("Genre has been deleted");
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
 * LIST
 */

var Genre = function(genre) {
    var el = $('<div/>', {'class': 'G-wrap', 'genreId': genre['genre_id']});
    var sc = new StateCookie('CP_GENRES');
    var hasChildren = genre['childNodes'] != '';

    el.disableTextSelect();

    var PLUS = '/site/i/icons/toggle-small-expand.png';
    var MINUS = '/site/i/icons/toggle-small.png';
    var LEAF = '/site/i/icons/toggle-node.png';

    var row = $('<div/>', {'class': 'G-head'}).appendTo(el);

    var collapsed = sc.getState(genre['genre_id']);
    var img = $('<img/>', {
        src: hasChildren ? collapsed ? PLUS : MINUS : LEAF,
        width: 16,
        height: 16
    }).appendTo(row);
    $('<span/>').text(genre['name']).click(function() {
        openDialog(genre);
    }).appendTo(row);

    if (!hasChildren) {
        el.addClass('G-nonexpandable');
    } else {
        el.addClass('G-expandable');
        img.click(function () {
            if (list.css('display') == 'none') {
                img.attr('src', MINUS);
                list.slideDown('fast');
                sc.setState(genre['genre_id'], false);
            } else {
                img.attr('src', PLUS);
                list.slideUp('fast');
                sc.setState(genre['genre_id'], true);
            }
        });

        var list = $('<div/>', {'class': 'G-list'}).css({'display': collapsed ? 'none' : 'block'}).appendTo(el);

        for (var i in genre['childNodes']) {
            var d = $('<div/>', {
                'class': 'G-item',
                'genreId': genre['childNodes'][i]['genre_id']
            }).appendTo(list);
            $('<span/>', {
                'text': genre['childNodes'][i]['name'],
                'index': i
            }).click(function() {
                openDialog(genre['childNodes'][$(this).attr('index')]);
            }).appendTo(d);
        }
    }

    return {
        getElement: function() {
            return el;
        }
    }
};

var Genres = function(t) {

    function redraw(el) {
        var c = $(el);
        for (var i in t) {
            var g = new Genre(t[i]);
            g.getElement().appendTo(c);
        }
    }

    return {
        redraw: redraw
    }
};

