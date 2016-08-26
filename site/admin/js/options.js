var POST_FILE = 'options.php';
var JSON = 'json';
var idPlaceholder = '#Options';

$(onLoad);

function onLoad() {
    $('#AddOption').click(clickAddOption);
    optionsReload();
}

function optionsReload() {
    var data = {
        'Action': 'get'
    };
    $.post(POST_FILE, data, callbackGet, JSON);
}

function callbackGet(result) {
    $(idPlaceholder).empty();
    $.each(result['options'], function(i, v) {
        new Option(v, optionsReload).appendTo(idPlaceholder);
    });
}

function clickAddOption() {
    new Option(null, optionsReload).prependTo(idPlaceholder);
    return false;
}

var Option = function(val, callback) {
    var el = $('<div class="Option-container">');
    var data = val || {
        'option_id': '',
        'option_name': '',
        'option_value': '',
        'autoload': '1'
    };

    var name = $('<input type="text" placeholder="Name">').val(data['option_name']);
    var value = $('<textarea placeholder="Value">').val(data['option_value']);
    var auto = $('<input type="checkbox">').attr('checked', data['autoload'] == '1');
    var label = $('<label>').append(auto).append('<span> Autoload</span>');

    var buttonSave = $('<button class="magenta Button Small">Save</button>').click(saveOption);
    var buttonDelete = $('<button class="blue Button Small">Delete</button>').click(deleteOption);

    $('<div>').append(name).append(label).appendTo(el);
    $('<div>').append(value).appendTo(el);
    $('<div style="height: 22px; float: left;">').append(buttonSave).appendTo(el);
    $('<div style="height: 22px; float: right;">').append(buttonDelete).appendTo(el);
    $('<br clear="all">').appendTo(el);

    function saveOption() {
        var row = {
            'Action': 'save',
            'option': {
                'option_id': data['option_id'],
                'option_name': name.val(),
                'option_value': value.val(),
                'autoload': auto.is(':checked') ? '1' : 0
            }
        };
        $.post(POST_FILE, row, callback, JSON);
    }

    function deleteOption() {
        if (confirm("Do you really want to delete this option?")) {
            if (data['option_id'] == '') {
                el.remove();
            } else {
                var row = {
                    'Action': 'delete',
                    'option_id': data['option_id']
                };
                $.post(POST_FILE, row, callback, JSON);
            }
        }
    }

    return el;
};

