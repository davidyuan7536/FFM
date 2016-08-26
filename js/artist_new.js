$(function() {
    $(MANDATORY_FIELDS).change(function() {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
        } else {
            $(this).removeClass('F-error');
        }
    });
    $('#ButtonSave').click(saveProfile);
});

var MANDATORY_FIELDS = '#ProfileName';

function onSubmitCheck() {
    var result = true;
    $(MANDATORY_FIELDS).each(function () {
        if ($(this).val() == '') {
            $(this).addClass('F-error');
            $(this).focus();
            return result = false;
        }
    });
    return result;
}

function saveProfile() {
    if (onSubmitCheck()) {
        var data = {
            'Name': $('#ProfileName').val(),
            'NameRu': $('#ProfileNameRu').val()
        };
        $.post(self.location, data, onSave, 'json');
    }
}

function onSave(result) {
    if (result['message']) {
        alert(result['message']);
    } else {
        window.location = result['url'];
    }
}
