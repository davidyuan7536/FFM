var MONTHS;
var articleId;
var PIC_SELECTION = false;
var PIC_DIALOG;

function loadArticleAndYears(_articleId) {
    articleId = _articleId;

    $('<option/>', {
        value: 'attached',
        text: 'Attached pictures'
    }).appendTo($('#Years'));

    loadYears();
}

function loadYears() {
    $.post('pictures.php', {'action': 'years'}, function(result) {
        $.each(result, function(i, v) {
            $('<option/>', {
                value: v['picture_year'],
                text: v['picture_year']
            }).appendTo($('#Years'));
        });
        $('#Years').change(yearsOnChange);
        $('#Years option:first-child').attr('selected', 'selected');
        yearsOnChange();
    }, 'json');
}

function yearsOnChange() {
    if ($('#Years').val() == 'attached') {
        var PICS;
        var inner = $('<div/>');
        $('#List').empty().append(inner);
        loadPicturesByArticleId(inner, PICS, articleId);
    } else {
        $('#List').empty().text('Loading...');

        var data = {
            'action': 'months',
            'year': $('#Years').val()
        };

        $.post('pictures.php', data, function(result) {
            $('#List').empty();
            MONTHS = [];

            $.each(result, function(i, v) {
                MONTHS.push(new PictureMonth(data['year'], v['picture_month']));
            });

            $.each(MONTHS, function(i, v) {
                $('#List').append(v.getElement());
            });
        }, 'json');
    }
}

var PictureMonth = function(year, month) {
    var PLUS = '/site/i/icons/toggle-small-expand.png';
    var MINUS = '/site/i/icons/toggle-small.png';
    var PICS = [];

    var el = $('<div/>', {
        'class': 'Pic-Month'
    });

    var head = $('<div/>', {'class': 'Pic-Head'}).click(function() {
        if (!content) {
            content = $('<div/>', {
                'class': 'Pic-Content'
            }).appendTo(el);
            img.attr('src', MINUS);
            loadPicturesByMonth(content, PICS, year, month);
        } else {
            if (content.css('display') == 'none') {
                img.attr('src', MINUS);
                content.slideDown('fast');
            } else {
                img.attr('src', PLUS);
                content.slideUp('fast');
            }
        }
    }).appendTo(el);

    var img = $('<img/>', {
        src: PLUS,
        width: 16,
        height: 16
    }).appendTo(head);
    $('<span/>').text(month).appendTo(head);

    var content;

    return {
        getElement: function() {
            return el;
        }
    }
};

function loadPicturesByMonth(el, PICS, year, month) {
    $(el).empty().text('Loading...');

    var data = {
        'action': 'pictures',
        'year': year,
        'month': month
    };

    loadPictures(el, PICS, data);
}

function loadPicturesByArticleId(el, PICS, articleId) {
    $(el).empty().text('Loading...');

    var data = {
        'action': 'article',
        'articleId': articleId
    };

    loadPictures(el, PICS, data);
}

function showDialog(picture) {
    if (PIC_DIALOG) {
        var path = PATH_PICTURES + picture['picture_year'] + '/' + picture['picture_month'] + '/';
        var o_link = path + picture['o_filename'];
        var m_link = path + picture['m_filename'];
        var s_link = path + picture['s_filename'];
        if (picture['m_filename'] == '') {
            $('#M_PIC', PIC_DIALOG).text('').attr('href', '#');
        } else {
            $('#M_PIC', PIC_DIALOG).text(m_link).attr('href', m_link);
        }
        $('#O_PIC', PIC_DIALOG).text(o_link).attr('href', o_link);
        $('#S_PIC', PIC_DIALOG).text(s_link).attr('href', s_link);
        PIC_DIALOG.attr('GUID', picture['picture_id']);
        PIC_DIALOG.dialog('open');
    }
}

function deletePicture(el, PICS, request) {
    $.post('pictures.php', {'action': 'delete', 'Id': PIC_DIALOG.attr('GUID')}, function(result) {
        if (result != 'OK') {
            alert(result);
        }
        PIC_DIALOG.dialog('close');
        loadPictures(el, PICS, request);
    });
}

function loadPictures(el, PICS, request) {
    $.post('pictures.php', request, function(result) {
        $(el).empty();
        PICS = [];

        $.each(result, function(i, v) {
            PICS.push(new PictureThumbnail(v));
        });

        $.each(PICS, function(i, v) {
            $(el).append(v.getElement());
        });

        if (PIC_SELECTION) {
            $(el).selectable({filter: '.Pic-Item'});
        } else {
            if (!PIC_DIALOG) {
                PIC_DIALOG = $('<div/>', { title: 'Picture' });

                PIC_DIALOG.dialog({
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

                var wrap = $('<div/>').appendTo(PIC_DIALOG);

                $('<div/>', {
                    'class': 'Trunc',
                    html: 'Original size: <a href="#" id="O_PIC" target="_blank">None</a>'
                }).appendTo(wrap);

                $('<div/>', {
                    'class': 'Trunc',
                    html: 'Medium size: <a href="#" id="M_PIC" target="_blank">None</a>'
                }).appendTo(wrap);

                $('<div/>', {
                    'class': 'Trunc',
                    html: 'Square size: <a href="#" id="S_PIC" target="_blank">None</a>'
                }).appendTo(wrap);

                var buttons = $('<div/>').css({'padding-top': '16px'}).appendTo(PIC_DIALOG);

                $('<button/>', {
                    'class': 'Button Small',
                    text: 'Delete',
                    css: {'float': 'right'},
                    click: function() {
                        if (confirm("Do you really want to delete this picture?")) {
                            deletePicture(el, PICS, request)
                        }
                    }
                }).appendTo(buttons);

                $('<button/>', {
                    'class': 'Button blue F-button',
                    text: 'Close',
                    click: function() {
                        PIC_DIALOG.dialog('close');
                    }
                }).appendTo(buttons);
            }
        }

        $('<div class="clear"></div>').appendTo(el);
    }, 'json');
}

var PictureThumbnail = function(data) {
    var xI = 30;
    var yI = -40;
    var path = PATH_PICTURES + data['picture_year'] + '/' + data['picture_month'] + '/';
    var file = data['m_filename'] != '' ? data['m_filename'] : data['o_filename'];
    var previewId = "preview" + data['picture_id'];
    var preview = "#" + previewId;

    var el = $('<div/>', {
        'class': 'Pic-Item-wrap'
    });

    var item = $('<div/>', {
        'class': 'Pic-Item',
        'GUID': path + data['o_filename'],
        'pictureWidth': data['o_width'],
        'pictureHeight': data['o_height']
    }).appendTo(el);

    var img = $('<img/>', {
        src: path + data['s_filename']
    }).appendTo(item);
    var text = $('<div/>', {
        text: data['picture_filename']
    }).appendTo(item);

    if (!PIC_SELECTION) {
        text.addClass('Link');
        text.click(function() {
            showDialog(data);
        });
    }

    img.hover(function(e) {
        $("body").append("<div id='" + previewId + "' class='Pic-Preview'><img src='" + path + file + "' alt='' />" + data['picture_filename'] + "</div>");
        $(preview)
                .css("top", (e.pageY + yI) + "px")
                .css("left", (e.pageX + xI) + "px")
                .fadeIn("fast");
    }, function() {
        $(preview).remove();
    });
    img.mousemove(function(e) {
        $(preview)
                .css("top", (e.pageY + yI) + "px")
                .css("left", (e.pageX + xI) + "px");
    });

    return {
        getElement: function() {
            return el;
        }
    }
};
