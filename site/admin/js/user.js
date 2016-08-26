
$(document).ready(function() {
    $('.F-wrap').FormNavigate("Leaving the page will lost in unsaved data!");

    initFormElements();

    $('#Name').focus();
});


/**
 *
 */
function save() {
    if ($('#Hash').val() == '') {
        $('#Hash').addClass('F-Error');
        $('#Hash').focus();
        return;
    }

    if ($('#Name').val() == '') {
        $('#Name').addClass('F-Error');
        $('#Name').focus();
        return;
    }

    if ($('#Email').val() == '') {
        $('#Email').addClass('F-Error');
        $('#Email').focus();
        return;
    }

    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Hash': $('#Hash').val(),
        'Name': $('#Name').val(),
        'Email': $('#Email').val(),
        'Password': $('#Password').val(),
        'Enabled': $('#Enabled:checked').val() === undefined ? '0' : '1',
        'Subscribe': $('#Subscribe:checked').val() === undefined ? '0' : '1'
    };

    $.post('user.php', data, function(result) {
        if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}


function cancel() {
    window.location = '/site/admin/users.php';
}


/**
 *
 */
function initFormElements() {
    $('#Hash').change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });
    $('#Name').change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });
    $('#Email').change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-Error');
        } else {
            $(this).removeClass('F-Error');
        }
    });
}
