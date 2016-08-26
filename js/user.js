$(function() {
    $('#UserSearch').keyup(userSearch).change(userSearch).focus(focusUserSearch);
    $(document).click(function(e) {
        $('#UserSuggest').fadeOut('fast');
    });
    $('#UserSearch,#UserSuggest').click(function(event) {
        event.stopPropagation();
    });
    $('#popupRequest').dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 360,
        position: 'center',
        open: function(event, ui) {
            $('#fqForm input[type=text],#fqForm textarea').val('');
            $('#fqEmail').focus();
        },
        close: function(event, ui) {
        }
    });
    $('#fqForm').submit(function() {
        doRequest();
        return false;
    });
    loadList();
});


/******************************************************************************
 *
 * REQUEST
 *
 */
function doRequest() {
    var data = {
        'Action': 'Request',
        'Id': $('#fqId').val(),
        'Email': $('#fqEmail').val(),
        'Text': $('#fqText').val()
    };

    $('#fqMessage').fadeOut();
    $('#fqForm .F-message').fadeOut();
    $('#fqForm input[type=text],#fqForm textarea').removeClass('F-error');

    $.post(self.location, data, function(result) {
        if (result['status'] == 'OK') {
            //            $("#fqMessage").html(result['message']);
            //            $("#fqMessage").fadeIn('fast');
            //            $("#fqForm").hide();
            $('#popupRequest').dialog('close');
            loadList();
        } else {
            if (result['message']) {
                $("#fqMessage").html(result['message']);
                $("#fqMessage").fadeIn('fast');
            }
            if (result['fields']) {
                $.each(result['fields'], function(i, v) {
                    $("#fq" + i).addClass('F-error');
                });
            }
        }
    }, 'json');
}


/******************************************************************************
 *
 * LIST
 *
 */
function loadList() {
    var data = {
        'Action': 'Load'
    };

    $.post(self.location, data, function(result) {
        $('#pmList').empty();

        $.each(result, function(i, v) {
            var el = $('<div/>').appendTo('#pmList');
            if (v['promoter_id']) {
                $('<div class="suggest-name"><a href="/promoters/' + v['promoter_filename'] + '.html"><img width="50" height="50" alt="" src="' + getPromoterPicture(v['promoter_image'], 's') + '"> ' + $.escape(v['promoter_name']) + '</a></div>').appendTo(el);
            } else {
                $('<div class="suggest-name"><a href="/artists/' + v['filename'] + '.html"><span></span><img width="50" height="50" alt="" src="' + getArtistPicture(v['image'], 's') + '"> ' + $.escape(v['name']) + '</a></div>').appendTo(el);
            }
        });

        if (result.length > 0) {
            $('#pmList').fadeIn('fast');
        } else {
            $('#pmList').fadeOut('fast');
        }
    }, 'json');
}


/******************************************************************************
 *
 * SEARCH
 *
 */
var UserSearch = '';
var UserSearching = null;
var UserSearchStart = false;

function initSearchFields() {
    $('#UserSuggest').hide();
    $('#UserSearch').val('');
    UserSearch = '';
    UserSearchStart = false;
}

function userSearch() {
    var s = $('#UserSearch').val();
    if (s != '' && s != UserSearch) {
        if (UserSearching == null) {
            UserSearch = s;
            var data = {
                'Action': 'Search',
                'Search': s
            };
            UserSearching = $.post(self.location, data, onUserSearch, 'json');
        } else {
            UserSearchStart = true;
        }
    }
}

function focusUserSearch() {
    var s = $('#UserSearch').val();
    if (s != '' && s == UserSearch) {
        $('#UserSuggest').fadeIn('fast');
    } else {
        userSearch();
    }
}

function onUserSearch(result) {
    $('#UserSuggest').empty();

    $.each(result, function(i, v) {
        var el = $('<div class="User-suggest-item"/>').click(function() {
            $('#fqId').val(v['artist_id']);
            $('#fqArtist').text(v['name']);
            $('#fqImage').attr('src', $('img', this).attr('src'));
            $('#popupRequest').dialog('open');
        }).appendTo('#UserSuggest');
        $('<div><img style="vertical-align:-10px;cursor:default;" width="30" height="30" alt="" src="' + getArtistPicture(v['image'], 's') + '"> ' + $.escape(v['name']) + '</div>').appendTo(el);
    });

    if (result.length > 0) {
        $('#UserSuggest').fadeIn('fast');
    } else {
        $('#UserSuggest').fadeOut('fast');
    }

    UserSearching = null;

    if (UserSearchStart) {
        UserSearchStart = false;
        userSearch();
    }
}


