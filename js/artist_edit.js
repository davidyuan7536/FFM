var profileGenreSearch;

$(function() {
    $('#ButtonEdit').click(editProfile);
    $('#ButtonSave').click(saveProfile);
    $('#ButtonCancel').click(closeProfile);

    $('#ProfileDescription,#ProfileLinks,#ProfileExtra').autoResize({animate:false,extraSpace:16});
    $('textarea[maxlength]').limitMaxLength();

    profileGenreSearch = new GenreSearch('#GSearch', self.location, checkAndAddGenre);
});

function addGenre(value, el) {
    $('<span/>', {
        'class': 'A-tags-edit',
        'text': value['name'],
        'data': value,
        'click': function() {
            $(this).remove();
        }
    }).appendTo(el);
}


/******************************************************************************
 *
 * EVENTS
 *
 */
var EVENT_FIELDS = '#EventName,#EventDescription,#EventAddress';
var EVENT_DATE = '#EventDate';
var eventImageUploader;

function initEventEdit() {
    $(EVENT_FIELDS).autoResize({animate:false,extraSpace:0});
    $(EVENT_FIELDS).limitMaxLength();
    $(EVENT_FIELDS).change(checkEl).blur(checkEl);
    $(EVENT_DATE).datepicker({
        showOn: 'button',
        buttonImage: '/i/icons/date.png',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd 18:00:00',
        onSelect: setDate
    });
    $('#EventDateLabel').click(function() {
        $(EVENT_DATE).datepicker('show');
    });
    $('#EventCancel').click(closeEventForm);
    $('#EventSave').click(saveEvent);

    var UPLOADER_SETTINGS = {
        upload_url: self.location.pathname,

        file_size_limit: "4 MB",
        file_types: "*.jpg",
        file_types_description: "JPG Images",

        button_placeholder_id: 'EventImageUploaderHolder',
        button_width: 90,
        button_height: 50,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,

        file_dialog_start_handler: getEventId,

        custom_settings: {
            upload_target: '#EventImageUploaderStatus',
            callback: function(data) {
                try {
                    var r = $.parseJSON(data);
                    if (r['message']) {
                        $('#EventImageUploaderStatus').text(r['message']);
                    } else if (r['image']) {
                        $('#EventImage').attr('src', '/thumbnails/events/' + r['image'] + '.jpg?' + Math.random());
                    }
                } catch(e) {
                }
            }
        }
    };
    var POST_PARAMS = {
        'Action': 'SaveEventImage',
        'Id': ''
    };
    eventImageUploader = new Uploader(UPLOADER_SETTINGS, POST_PARAMS);
}

function getEventId() {
    eventImageUploader.getInstance().addPostParam('Id', $('#EventId').val());
}

function initEventList() {
    $('.A-Events-row-edit').click(editEvent);
    $('.A-Events-row-delete').click(deleteEvent);
}

function setDate() {
    var s = $.datepicker.formatDate('MM d, DD', $(EVENT_DATE).datepicker('getDate'));
    $('#EventDateLabel').text(s).removeClass('F-error');
}

function editEvent() {
    var data = {
        'Action': 'GetEvent',
        'Id': $(this).val()
    };
    $.post(self.location, data, function(result) {
        addEventForm();
        $('#eventImageUploadNotification').hide();
        $('#eventImageUpload').show();
        $('#EventEdit').insertAfter($('#Row' + result['event_id']).hide()).show();
        $('#EventId').val(result['event_id']);
        $('#EventAddress').val(result['event_address']).trigger('change.dynSiz');
        $('#EventDescription').val(result['event_description']).trigger('change.dynSiz');
        $('#EventName').val(result['event_name']).trigger('change.dynSiz');
        $(EVENT_DATE).val(result['event_date']);
        $('#EventGeoSearchId').val(result['geo_tag_id']);
        $('#EventGeoSearch').val(result['geo_tag_text']);
        if (result['event_image'] != '') {
            $('#EventImage').attr('src', '/thumbnails/events/' + result['event_image'] + '.jpg?' + Math.random());
        } else {
            $('#EventImage').attr('src', '/i/decor/placeholder-article.png');
        }
        setDate();
    }, 'json');
}

function addEventForm() {
    closeEventForm();
    $('#eventImageUploadNotification').show();
    $('#eventImageUpload').hide();
    $('#EventDateLabel').text($('#EventDateLabel').attr('title')).removeClass('F-error');
    $('#EventCancel').show();
    $('#EventDelete').hide();
    $('#EventEdit').fadeIn();
    $(EVENT_FIELDS).val('').removeClass('F-error').trigger('change.dynSiz');
    $(EVENT_DATE).val('');
    $('#EventId').val('');
    $('#EventGeoSearchId,#EventGeoSearch').val('');
    $('#EventImageUploaderStatus').val('');
}

function closeEventForm() {
    $('#EventEdit').hide().appendTo('#EventsNew');
    var v = $('#EventId').val();
    if (v != '') {
        $('#Row' + v).show();
    }
}

function checkEl() {
    if ($(this).val() == '') {
        $(this).addClass('F-error');
    } else {
        $(this).removeClass('F-error');
    }
}

function saveEvent() {
    var result = true;
    $(EVENT_FIELDS).each(function () {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
            $(this).focus();
            return result = false;
        }
    });

    if (result && $(EVENT_DATE).val() == '') {
        $('#EventDateLabel').addClass('F-error');
        result = false;
    }

    if (result) {
        var data = {
            'Action': 'SaveEvent',
            'Id': $('#EventId').val(),
            'Name': $('#EventName').val(),
            'Description': $('#EventDescription').val(),
            'Address': $('#EventAddress').val(),
            'Date': $(EVENT_DATE).val(),
            'GeoSearchId': $('#EventGeoSearchId').val(),
            'GeoSearch': $('#EventGeoSearch').val()
        };
        $.post(self.location, data, onEventSave, 'json');
    }
}

function onEventSave(result) {
    if (!$.isEmptyObject(result)) {
        if (result['message']) {
            alert(result['message'])
        } else {
            closeEventForm();
            $.each(result['elements'], function(key, value) {
                $(key).empty().html(value);
            });
        }
    }
}

function deleteEvent() {
    if (confirm($('#EventDeleteConfirmation').val())) {
        var data = {
            'Action': 'DeleteEvent',
            'Id': $(this).val()
        };

        $.post(self.location, data, onEventSave, 'json');
    }
}


/******************************************************************************
 *
 * RELEASES
 *
 */
var REL_FIELDS = '#ReleaseName,#ReleaseYear,#ReleaseLabel';
var releaseGenreSearch;
var releaseImageUploader;
var TRACK_FIELDS = '#TrackName';
var trackGenreSearch;
var trackUploader;
var audioTrack;

function initReleaseForm() {
    ///////////////////////////////////////////////////////////////////////////
    // RELEASE
    $(REL_FIELDS).change(checkEl).blur(checkEl);
    $('#ReleaseWindow').dialog({
        'autoOpen': false,
        'modal': true,
        'width': 500,
        'height': 350,
        'position': 'center',
        close: function(event, ui) {
            releaseGenreSearch.hideSuggest();
        }
    });
    $('#ReleaseSave').click(saveRelease);
    $('#ReleaseCancel').click(closeReleaseDialog);
    releaseGenreSearch = new GenreSearch('#ReleaseGSearch', self.location, onGenreSelectRelease);

    ///////////////////////////////////////////////////////////////////////////
    // TRACK
    $(TRACK_FIELDS).change(checkEl).blur(checkEl);
    $('#TrackWindow').dialog({
        'autoOpen': false,
        'modal': true,
        'width': 500,
        'height': 600,
        'position': 'center',
        close: function(event, ui) {
            trackGenreSearch.hideSuggest();
            audioTrack && audioTrack.pause();
        }
    });
    $('#TrackSave').click(saveTrack);
    $('#TrackCancel').click(closeTrackDialog);
    trackGenreSearch = new GenreSearch('#TrackGSearch', self.location, onGenreSelectTrack);
}

function initReleaseList() {
    $('.A-Releases-row-edit').click(function() {
        editRelease($(this).val());
    });
    $('.A-Releases-row-delete').click(deleteRelease);
}

function editRelease(id) {
    if (id) {
        var data = {
            'Action': 'GetRelease',
            'Id': id
        };
        $.post(self.location, data, onLoadRelease, 'json');
    } else {
        resetReleaseFields();
        openReleaseDialog();
    }
}

function resetReleaseFields() {
    $(REL_FIELDS).val('').removeClass('F-error');
    $('#ReleaseId').val('');
    $('#ReleaseGSearch').val('');
    $('#ReleaseGList').empty();
    $('#ReleaseImageUploaderStatus').empty();
    var i = $('#ReleaseImage');
    i.attr('src', i.attr('origin'));
}

function getReleaseId() {
    releaseImageUploader.getInstance().addPostParam('Id', $('#ReleaseId').val());
}

function openReleaseDialog() {
    $('#ReleaseWindow').dialog('open');

    if (!releaseImageUploader) {
        var UPLOADER_SETTINGS = {
            upload_url: self.location.pathname,

            file_size_limit: "4 MB",
            file_types: "*.jpg",
            file_types_description: "JPG Images",

            button_placeholder_id: 'ReleaseImageUploaderHolder',
            button_width: 130,
            button_height: 130,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            file_dialog_start_handler: getReleaseId,

            custom_settings: {
                upload_target: '#ReleaseImageUploaderStatus',
                callback: function(data) {
                    try {
                        var r = $.parseJSON(data);
                        if (r['message']) {
                            $('#ReleaseImageUploaderStatus').text(r['message']);
                        } else if (r['image']) {
                            var u = r['image'] + '/cover_m.jpg?' + Math.random();
                            $('#ReleaseImage').attr('src', u);
                        }
                    } catch(e) {
                    }
                }
            }
        };
        var POST_PARAMS = {
            'Action': 'SaveReleaseImage',
            'Id': ''
        };
        releaseImageUploader = new Uploader(UPLOADER_SETTINGS, POST_PARAMS);
    }

    if ($('#ReleaseId').val() == '') {
        $('#ReleaseImageWrap').hide();
        $('#ReleaseImageNotification').show();
    } else {
        $('#ReleaseImageWrap').show();
        $('#ReleaseImageNotification').hide();
    }
}

function closeReleaseDialog() {
    $('#ReleaseWindow').dialog('close');
}

function saveRelease() {
    var result = true;
    $(REL_FIELDS).each(function () {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
            $(this).focus();
            return result = false;
        }
    });

    //    if (result && $(EVENT_DATE).val() == '') {
    //        $('#EventDateLabel').addClass('F-error');
    //        result = false;
    //    }

    if (result) {
        var genres = [];
        $('.A-tags-edit', '#ReleaseWindow').each(function() {
            genres.push($(this).data()['genre_id']);
        });
        var data = {
            'Id': $('#ReleaseId').val(),
            'Name': $('#ReleaseName').val(),
            'Year': $('#ReleaseYear').val(),
            'Label': $('#ReleaseLabel').val(),
            'GenresIds': genres,
            'Action': 'SaveRelease'
        };
        $.post(self.location, data, onSaveRelease, 'json');
    }
}

function deleteRelease() {
    if (confirm($('#ReleaseDeleteConfirmation').val())) {
        var data = {
            'Action': 'DeleteRelease',
            'Id': $(this).val()
        };

        $.post(self.location, data, onSaveRelease, 'json');
    }
}

function onLoadRelease(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        resetReleaseFields();
        $.each(result['genres'], function(key, value) {
            addGenre(value, '#ReleaseGList');
        });
        $.each(result['elements'], function(key, value) {
            $(key).val(value);
        });
        if (result['image'] != '') {
            $('#ReleaseImage').attr('src', result['image'] + '/cover_m.jpg?' + Math.random());
        }
        openReleaseDialog();
    }
}

function onSaveRelease(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key).html(value);
        });
        closeReleaseDialog();
    }
}

function onGenreSelectRelease(value) {
    var exists = false;
    $('.A-tags-edit').each(function() {
        if ($(this).data()['genre_id'] == value['genre_id']) {
            exists = true;
        }
    });
    if (!exists) {
        addGenre(value, '#ReleaseGList');
    }
}


/******************************************************************************
 *
 * AUDIO TRACK
 *
 */
function initTrackList() {
    $('.A-Track-edit').click(function(event) {
        event.stopPropagation();
        editTrack($(this).val());
    });
    $('.A-Track-delete').click(deleteTrack);
}

function addAudio(url) {
    audioTrack = new Audio();
    audioTrack.autobuffer = false;
    audioTrack.preload = 'none';
    audioTrack.controls = true;
    audioTrack.src = url;
    audioTrack.volume = 0.5;
    $('#TrackAudio').empty().append(audioTrack);
}

function editTrack(id) {
    if (id) {
        var data = {
            'Action': 'GetTrack',
            'Id': id
        };
        $.post(self.location, data, onLoadTrack, 'json');
    } else {
        resetTrackFields();
        openTrackDialog();
    }
}

function resetTrackFields() {
    $(TRACK_FIELDS).val('').removeClass('F-error');
    $('#TrackId,#TrackYear,#TrackLabel,#TrackGSearch,#TrackKeywords,#TrackDescription').val('');
    $('#TrackGList,#TrackUploadStatus,#TrackAudio').empty();
    $('#TrackShare').attr('checked', true);
}

function getTrackId() {
    trackUploader.getInstance().addPostParam('Id', $('#TrackId').val());
}

function openTrackDialog() {
    $('#TrackWindow').dialog('open');

    if (!trackUploader) {
        var UPLOADER_SETTINGS = {
            upload_url: self.location.pathname,

            file_size_limit: "20 MB",
            file_types: "*.mp3",
            file_types_description: "MP3 files",

            button_placeholder_id: 'TrackUploadHolder',
            button_width: 80,
            button_height: 16,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            file_dialog_start_handler: getTrackId,

            custom_settings: {
                upload_target: '#TrackUploadStatus',
                callback: function(data) {
                    try {
                        var r = $.parseJSON(data);
                        if (r['message']) {
                            $('#TrackUploadStatus').text(r['message']);
                        } else if (r['mp3'] != '') {
                            addAudio(r['mp3']);
                        }
                    } catch(e) {
                    }
                }
            }
        };
        var POST_PARAMS = {
            'Action': 'SaveTrackAudio',
            'Id': ''
        };
        trackUploader = new Uploader(UPLOADER_SETTINGS, POST_PARAMS);
    }

    if ($('#TrackId').val() == '') {
        $('#TrackUploadWrap').hide();
        $('#TrackUploadNotification').show();
    } else {
        $('#TrackUploadWrap').show();
        $('#TrackUploadNotification').hide();
    }
}

function closeTrackDialog() {
    $('#TrackWindow').dialog('close');
}

function saveTrack() {
    var result = true;
    $(TRACK_FIELDS).each(function () {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
            $(this).focus();
            return result = false;
        }
    });

    if (result) {
        var genres = [];
        $('.A-tags-edit', '#TrackWindow').each(function() {
            genres.push($(this).data()['genre_id']);
        });
        var data = {
            'Id': $('#TrackId').val(),
            'Name': $('#TrackName').val(),
            'Year': $('#TrackYear').val(),
            'Label': $('#TrackLabel').val(),
            'GenresIds': genres,
            'Keywords': $('#TrackKeywords').val(),
            'Description': $('#TrackDescription').val(),
            'Share': $('#TrackShare:checked').length,
            'ReleaseHash': $('#TrackReleaseHash').val(),
            'Action': 'SaveTrack'
        };
        $.post(self.location, data, onSaveTrack, 'json');
    }
}

function deleteTrack(event) {
    event.stopPropagation();
    if (confirm($('#TrackDeleteConfirmation').val())) {
        var data = {
            'Action': 'DeleteTrack',
            'Id': $(this).val()
        };

        $.post(self.location, data, onSaveTrack, 'json');
    }
}

function onLoadTrack(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        resetTrackFields();
        $.each(result['genres'], function(key, value) {
            addGenre(value, '#TrackGList');
        });
        $.each(result['elements'], function(key, value) {
            var el = $(key);
            if (el.is('[type=checkbox]')) {
                $(el).attr('checked', value == 1);
            } else {
                el.val(value);
            }
        });
        if (result['mp3'] != '') {
            addAudio(result['mp3']);
        }
        openTrackDialog();
    }
}

function onSaveTrack(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key).html(value);
        });
        closeTrackDialog();
    }
}

function onGenreSelectTrack(value) {
    var exists = false;
    $('.A-tags-edit').each(function() {
        if ($(this).data()['genre_id'] == value['genre_id']) {
            exists = true;
        }
    });
    if (!exists) {
        addGenre(value, '#TrackGList');
    }
}


/******************************************************************************
 *
 * PROFILE CARD
 *
 */
var EL_VIEW = '#ProfileTitle,#ButtonEdit,#ProfileCard,#GeoTag,#ProfileGenres';
var EL_EDIT = '#ProfileTitleEdit,#ButtonSave,#ButtonCancel,#ProfileCardEdit,#ProfileGeoEdit,#ProfileGenresEdit';
var profileImageUploader;

function openProfile() {
    if (!profileImageUploader) {
        var UPLOADER_SETTINGS = {
            upload_url: self.location.pathname,

            file_size_limit: "4 MB",
            file_types: "*.jpg",
            file_types_description: "JPG Images",

            button_placeholder_id: 'ProfileImageUploaderHolder',
            button_width: 130,
            button_height: 130,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            custom_settings: {
                upload_target: '#ProfileImageUploaderStatus',
                callback: function(data) {
                    try {
                        var r = $.parseJSON(data);
                        if (r['message']) {
                            $('#ProfileImageUploaderStatus').text(r['message']);
                        } else if (r['image']) {
                            $('#ProfileImage').attr('src', r['image']);
                            $('#ProfileCard .A-card-image img').attr('src', r['image']);
                        }
                    } catch(e) {
                    }
                }
            }
        };
        var POST_PARAMS = {
            'Action': 'SaveProfileImage'
        };
        profileImageUploader = new Uploader(UPLOADER_SETTINGS, POST_PARAMS);
    }
    $(EL_VIEW).hide();
    $(EL_EDIT).show('fade');
    $('#ProfileImageUploaderStatus').empty();
}

function closeProfile() {
    $(EL_EDIT).hide();
    $(EL_VIEW).show();
    profileGenreSearch.resetFields();
}

function editProfile() {
    var data = {
        'Action': 'GetInfo'
    };
    $.post(self.location, data, onLoadProfile, 'json');
}

function saveProfile() {
    var genres = [];
    $('.A-tags-edit').each(function() {
        genres.push($(this).data()['genre_id']);
    });
    var data = {
        'Name': $('#ProfileName').val(),
        'NameRu': $('#ProfileNameRu').val(),
        'Description': $('#ProfileDescription').val(),
        'Links': $('#ProfileLinks').val(),
        'Extra': $('#ProfileExtra').val(),
        'Club': $('#ProfileClub').is(':checked') ? 2 : 1,
        'GenresIds': genres,
        'GeoSearchId': $('#GeoSearchId').val(),
        'GeoSearch': $('#GeoSearch').val(),
        'Action': 'SaveInfo'
    };
    $.post(self.location, data, onSaveProfile, 'json');
}

function onLoadProfile(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $('#ProfileGList').empty();
        $.each(result['genres'], function(key, value) {
            addGenre(value, '#ProfileGList');
        });
        openProfile();
        $.each(result['elements'], function(key, value) {
            if ($(key).is(':checkbox')) {
                $(key).attr('checked', value);
            } else {
                $(key).val(value).trigger('change.dynSiz');
            }
        });
    }
}

function onSaveProfile(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key).html(value);
        });
        closeProfile();
    }
}

function checkAndAddGenre(value) {
    var exists = false;
    $('.A-tags-edit').each(function() {
        if ($(this).data()['genre_id'] == value['genre_id']) {
            exists = true;
        }
    });
    if (!exists) {
        addGenre(value, '#ProfileGList');
    }
}


/******************************************************************************
 *
 * PHOTOS
 *
 */
var photoUploader;

function initPhotoEdit() {
    if (!photoUploader) {
        var UPLOADER_SETTINGS = {
            upload_url: self.location.pathname,

            file_size_limit: "6 MB",
            file_types: "*.jpg",
            file_types_description: "JPG Images",

            button_placeholder_id: 'PhotoUploaderHolder',
            button_width: 180,
            button_height: 35,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            custom_settings: {
                upload_target: '#PhotoUploaderStatus',
                callback: function(data) {
                    try {
                        console.log(data);
                        var r = $.parseJSON(data);
                        if (r['message']) {
                            $('#PhotoUploaderStatus').text(r['message']);
                        } else if (r['elements']) {
                            $.each(r['elements'], function(key, value) {
                                $(key).empty().html(value);
                            });
                            $('.Photos-wrap a').lightBox();
                        }
                    } catch(e) {
                        console.log(e);
                    }
                }
            }
        };
        var POST_PARAMS = {
            'Action': 'SavePhoto'
        };
        photoUploader = new Uploader(UPLOADER_SETTINGS, POST_PARAMS);
    }
    $('#PhotoUploaderStatus').empty();
    $('.Photos-action').live('click', function() {
        if (confirm($('#PhotoDeleteConfirmation').val())) {
            var data = {
                'Action': 'DeletePhoto',
                'Id': $(this).attr('photo')
            };
            $.post(self.location, data, onDeletePhoto, 'json');
        }
    });
}

function onDeletePhoto(result) {
    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key).html(value);
        });
        $('.Photos-wrap a').lightBox();
    }
}


/******************************************************************************
 *
 * UPLOADER
 *
 */
var Uploader = function(options, post_params) {
    var settings = {
        post_params: $.extend({
            'PHPSESSID': readCookie('PHPSESSID')
        }, post_params),

        file_queue_error_handler: fileQueueError,
        file_dialog_complete_handler: fileDialogComplete,
        upload_progress_handler: uploadProgress,
        upload_error_handler: uploadError,
        upload_success_handler: uploadSuccess,
        upload_complete_handler: uploadComplete,

        flash_url: "/js/swfupload.swf",
        debug: false,
        statusElement: $('<div/>')
    };

    $.extend(settings, options);

    var swfUpload = new SWFUpload(settings);

    /******************************************************************************
     *
     * UPLOADER HANDLERS
     *
     */
    function fileQueueError(file, errorCode, message) {
        $(this.customSettings.upload_target).text(message);
    }

    function fileDialogComplete(numFilesSelected, numFilesQueued) {
        if (numFilesQueued > 0) {
            this.startUpload();
        }
    }

    function uploadProgress(file, bytesLoaded) {
        var percent = Math.ceil((bytesLoaded / file.size) * 100);

        if (percent === 100) {
            $(this.customSettings.upload_target).text('Processing...');
        } else {
            $(this.customSettings.upload_target).text('Uploading... ' + percent + '%');
        }
    }

    function uploadError(file, errorCode, message) {
        $(this.customSettings.upload_target).text(message);
    }

    function uploadSuccess(file, serverData) {
        $(this.customSettings.upload_target).text('New file uploaded.');
        if (this.customSettings.callback) {
            this.customSettings.callback(serverData);
        }
    }

    function uploadComplete(file) {
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        }
    }

    return {
        getInstance: function() {
            return swfUpload;
        }
    }
};


/******************************************************************************
 *
 * GENRE SEARCH
 *
 */
var GenreSearch = function(fieldId, url, callback) {
    var gSearch = '';
    var gSearching = null;
    var gSearchStart = false;
    var fieldEl = $(fieldId);
    var suggestEl = $('<div class="DD-wrap" style="display: none;"></div>').appendTo('body');

    fieldEl.click(onClickElements).keyup(genreSearch).change(genreSearch).focus(focusGenreSearch);
    suggestEl.click(onClickElements);

    $(document).click(hideSuggest);

    function onClickElements(event) {
        event.stopPropagation();
    }

    function genreSearch() {
        var s = fieldEl.val();
        if (s != '' && s != gSearch) {
            if (gSearching == null) {
                gSearch = s;
                var data = {
                    'Action': 'Search',
                    'Search': s
                };
                gSearching = $.post(url, data, onGenreSearch, 'json');
            } else {
                gSearchStart = true;
            }
        }
    }

    function focusGenreSearch() {
        var s = fieldEl.val();
        s != '' && s == gSearch ? showSuggest() : genreSearch();
    }

    function showSuggest() {
        var p = fieldEl.offset();
        suggestEl.fadeIn('fast');
        suggestEl.offset({
            top: p.top + fieldEl.outerHeight(),
            left: p.left
        });
    }

    function hideSuggest() {
        suggestEl.fadeOut('fast');
    }

    function onGenreSearch(result) {
        suggestEl.empty();

        $.each(result, function(i, v) {
            $('<div/>', {
                'class': 'DD-item',
                'text': v['name'],
                'data': v,
                'click': function() {
                    callback(v);
                }
            }).appendTo(suggestEl);
        });

        result.length > 0 ? showSuggest() : hideSuggest();

        gSearching = null;

        if (gSearchStart) {
            gSearchStart = false;
            genreSearch();
        }
    }

    return {
        resetFields: function () {
            suggestEl.hide();
            fieldEl.val('');
            gSearch = '';
            gSearchStart = false;
        },
        hideSuggest: function() {
            suggestEl.hide();
        }
    }
};



