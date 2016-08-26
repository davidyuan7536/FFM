var releasesLoaded = false;
var releaseList;
var currentRelease;
var PROFILE_OPTIONS = {
    cache: true,
    spinner: '',
    panelTemplate: '<div>Loading&#8230;</div>',
    idPrefix: 'profile_tab',
    ajaxOptions: {type: 'POST'}
};

function VideoPlayer(el, w, h) {
    var flashvars;
    var params;
    var attributes;
    if (el.attr('videoService') == 'youtube') {
        flashvars = {};
        params = { allowscriptaccess: 'always', allowfullscreen: 'true' };
        attributes = {};
        swfobject.embedSWF('http://www.youtube.com/v/' + el.attr('videoId'), el.attr('id'), w, h, '9.0.0', '', flashvars, params, attributes);
    } else if (el.attr('videoService') == 'vimeo') {
        flashvars = { clip_id: el.attr('videoId'), show_portrait: 0, show_byline: 0, show_title: 0, color: 'ec0009' };
        params = { allowscriptaccess: 'always', allowfullscreen: 'true' };
        attributes = {};
        swfobject.embedSWF('http://vimeo.com/moogaloop.swf', el.attr('id'), w, h, '9.0.0', '', flashvars, params, attributes);
    }
}

$(function() {
    try {
        $('#Map').each(function() {
            var map = $(this);
            var MAP_OPTIONS = {
                zoom: parseInt(map.attr('zoom'), 10),
                center: new google.maps.LatLng(map.attr('lat'), map.attr('lng')),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            new google.maps.Map(document.getElementById('Map'), MAP_OPTIONS);
        });

        $('.video-frame').each(function() {
            VideoPlayer($(this), '280', '180');
        });
    } catch(e) {
    }

//    $('#Releases').click(function() {
//        if (releasesLoaded) {
//            $('#tabs').tabs('load', 0);
//        }
//    });

    $('.AA-image').live('mouseover', function() {
        $('.AA-title', $(this).next('.AA-content')).addClass('hover');
    });
    $('.AA-image').live('mouseout', function() {
        $('.AA-title', $(this).next('.AA-content')).removeClass('hover');
    });
});

function initReleaseListActions() {
    currentRelease = null;
    releaseList = $('#ReleaseList');
    $('.Release-preview .Release-select, .Release-preview .Release-image', releaseList).click(function() {
        selectRelease($(this).attr('target'));
    });
    $('.Release-view .Release-select, .Release-view .Release-image', releaseList).click(function() {
        var id = $(this).attr('target');
        if (id == currentRelease) {
            unSelectRelease();
        } else {
            selectRelease(id);
        }
    });
    $('.Release-preview .Release-image', releaseList).hover(
            function () {
                $(this).prev('.Release-select').addClass('Release-select-hover');
            },
            function () {
                $(this).prev('.Release-select').removeClass('Release-select-hover');
            }
            );
    $('.Release-view', releaseList).each(function() {
        var p = $(this);
        $('.Release-image', p).hover(
                function () {
                    $('.Release-select', p).addClass('Release-select-hover');
                },
                function () {
                    $('.Release-select', p).removeClass('Release-select-hover');
                }
                );
    });
    unSelectRelease();
    $('.Hello-Mini').miniPlayer('Hello-Mini');
    releasesLoaded = true;
}

function selectRelease(id) {
    currentRelease = id;
    var el = $('#' + id);
    releaseList.removeClass('Release-grid').addClass('Release-list');
    $('.Release-item', releaseList).removeClass('Release-selected').hide();
    el.addClass('Release-selected').show();
    var p = el.prevAll('.Release-item');
    if (p.length > 0) {
        $(p[0]).show();
    }
    var n = el.nextAll('.Release-item');
    if (n.length > 0) {
        $(n[0]).show();
    }
    $('.Release-add').hide();
    $('#TrackList').empty().text('Loading...').show();
    var data = {
        'Action': 'GetTrackList',
        'Id': id
    };
    $.post(self.location, data, onLoadTrackList, 'json');
}

function unSelectRelease() {
    releaseList.addClass('Release-grid').removeClass('Release-list');
    $('.Release-item', releaseList).removeClass('Release-selected').fadeIn();
    $('.Release-item-first', releaseList).hide();
    $('.Release-add').show();
    $('#TrackList').hide();
}

function onLoadTrackList(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key).empty().html(value);
        });
    }
}

var allVideos;
var allVideosLoaded = false;
var bodyScroll;

function showAllVideos() {
    if (!allVideos) {
        $('#AllVideos').dialog({
            'autoOpen': false,
            'modal': true,
            'width': 700,
            'position': 'center',
            open: function(event, ui) {
                $('#TwoVideos object').css('visibility', 'hidden');
                bodyScroll = $('body').css('overflow');
                $('body').css('overflow', 'hidden');
                if (!allVideosLoaded) {
                    var data = {
                        'Action': 'GetAllVideos'
                    };
                    $.post(self.location, data, onGetAllVideos, 'json');
                    allVideosLoaded = true;
                }
            },
            close: function(event, ui) {
                $('#TwoVideos object').css('visibility', 'visible');
                $('body').css('overflow', bodyScroll);
            }
        });
    }

    $('#AllVideos').dialog('open');
}

function onGetAllVideos(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key).empty().html(value);
        });
    }
}

