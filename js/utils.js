$(function() {
    authorization();

    $.escape = function(text) {
        return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
    };

    $.fn.extend({
        limitMaxLength: function() {
            return this.each(function() {
                $(this).keyup(function() {
                    var textarea = $(this);
                    var maxlength = parseInt(textarea.attr('maxlength'));
                    if (textarea.val().length > maxlength) {
                        textarea.val(textarea.val().substr(0, maxlength));
                    }
                });
            });
        }
    });

    var c = {
        init: function() {
            this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
            this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "an unknown version";
            this.OS = this.searchString(this.dataOS) || "an unknown OS"
        },
        searchString: function(b) {
            for (var a = 0; a < b.length; a++) {
                var d = b[a].string,e = b[a].prop;
                this.versionSearchString = b[a].versionSearch || b[a].identity;
                if (d) {
                    if (d.indexOf(b[a].subString) != -1)return b[a].identity
                } else if (e)return b[a].identity
            }
        },
        searchVersion: function(b) {
            var a = b.indexOf(this.versionSearchString);
            if (a != -1)return parseFloat(b.substring(a + this.versionSearchString.length + 1))
        },
        dataBrowser:[
            {string:navigator.userAgent,subString:"Chrome",identity:"Chrome"},
            {string:navigator.vendor,subString:"Apple",identity:"Safari",versionSearch:"Version"},
            {prop:window.opera,identity:"Opera"},
            {string:navigator.userAgent,subString:"Firefox",identity:"Firefox"},
            {string:navigator.userAgent,subString:"MSIE",identity:"Explorer",versionSearch:"MSIE"},
            {string:navigator.userAgent,subString:"Gecko",identity:"Mozilla",versionSearch:"rv"},
            {string:navigator.userAgent,subString:"Mozilla",identity:"Netscape",versionSearch:"Mozilla"}
        ],
        dataOS:[
            {string:navigator.platform,subString:"Win",identity:"Windows"},
            {string:navigator.platform,subString:"Mac",identity:"Mac"},
            {string:navigator.userAgent,subString:"iPhone",identity:"iPhone/iPod"},
            {string:navigator.platform,subString:"Linux",identity:"Linux"}
        ]};
    c.init();

    if ('Mac' == c.OS && 'Firefox' == c.browser) {
        $('body').addClass('macff');
    }

    $('.article-preview-wrap').each(function() {
        var item = $(this);
        var link = $('.article-preview-image a', item);
        if (link) {
            var title = $('.article-preview-title', item);
            link.mouseover(function() {
                title.addClass('hover');
            });
            link.mouseout(function() {
                title.removeClass('hover');
            });
        }
    });

    $('.AR-wrap').each(function() {
        var item = $(this);
        var link = $('.AR-photo a', item);
        if (link) {
            var title = $('.AR-name', item);
            artistsHovers(link, title);
        }
    });

    $('.event-info').each(function() {
        var item = $(this);
        var link = $('.event-photo a', item);
        if (link) {
            var title = $('.event-title', item);
            link.mouseover(function() {
                title.addClass('hover');
            });
            link.mouseout(function() {
                title.removeClass('hover');
            });
        }
    });

    $('#FSearch').keyup(quickSearch).change(quickSearch);
    $('#FSearch').blur(function() {
        $('#Suggest').delay(800).fadeOut('fast');
        if (searching) {
            stopSearch = true;
            searching = false;
        }
        search = '';
    }).focus(quickSearch);

    $('#ShareBlock').hover(
            function () {
                $('#SharePopup').show();
            },
            function () {
                $('#SharePopup').hide();
            }
            );
});

function openWin(url) {
    var defaultParams = {
        "width":       "800",   // Window width
        "height":      "600",   // Window height
        "top":         "0",     // Y offset (in pixels) from top of screen
        "left":        "0",     // X offset (in pixels) from left side of screen
        "directories": "no",    // Show directories/Links bar?
        "location":    "no",    // Show location/address bar?
        "resizeable":  "yes",   // Make the window resizable?
        "menubar":     "no",    // Show the menu bar?
        "toolbar":     "no",    // Show the tool (Back button etc.) bar?
        "scrollbars":  "yes",   // Show scrollbars?
        "status":      "no"     // Show the status bar?
    };

    var defaultConfig = {
        autoFocus: true
    };

    // Popup window defaults (don't leave it to the browser)
    var windowParams = $.extend(defaultParams, {});

    // Configuration properties
    var windowConfig = $.extend(defaultConfig, {});

    var i, paramString = "";

    for (i in windowParams) {
        if (windowParams.hasOwnProperty(i)) {
            paramString += (paramString === "") ? "" : ",";
            paramString += i + "=";

            // Allow true/false instead of yes/no in params
            if (windowParams[i] === true || windowParams[i] === false) {
                paramString += (windowParams[i]) ? "yes" : "no";
            }
            else {
                paramString += windowParams[i];
            }
        }
    }

    var popupWindow = window.open(url, 'new_window', paramString);

    if (windowConfig.autoFocus) {
        popupWindow.focus();
    }

    return popupWindow;
}

function createCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function changeLang(lang) {
    createCookie("lang", lang, 120);
    window.location.reload();
    return false;
}

function artistsHovers(link, title) {
    link.mouseover(function() {
        title.addClass('hover');
    });
    link.mouseout(function() {
        title.removeClass('hover');
    });
}

function getArtistPicture(image, size) {
    return image == '' ? '/i/decor/placeholder-artist_' + size + '.png' : image;
}

function getPromoterPicture(image, size) {
    return image == '' ? '/i/decor/placeholder-promoter_' + size + '.png' : image;
}

var search = '';
var searching = false;
var toSearch = false;
var stopSearch = false;

function quickSearch() {
    if ($('#FSearch').val() != '' && $('#FSearch').val() != search) {
        if (!searching) {
            search = $('#FSearch').val();

            var data = {
                'Search': $('#FSearch').val()
            };

            $.post('/search/', data, function(result) {
                if (stopSearch) {
                    stopSearch = false;
                    searching = false;
                } else {
                    $('#Suggest').empty();

                    $.each(result, function(i, v) {
                        var el = $('<div class="suggest-wrap"/>').appendTo('#Suggest');
                        $('<div class="suggest-name"><a href="/artists/' + v['filename'] + '.html"><span></span><img width="50" height="50" alt="" src="' + getArtistPicture(v['image'], 's') + '"> ' + $.escape(v['name']) + '</a></div>').appendTo(el);
                        $('<div class="clear"></div>').appendTo(el);
                    });

                    if (result.length > 0) {
                        $('#Suggest').fadeIn('fast');
                    } else {
                        $('#Suggest').fadeOut('fast');
                    }

                    searching = false;

                    if (toSearch) {
                        toSearch = false;
                        quickSearch();
                    }
                }
            }, 'json');

            searching = true;
        } else {
            toSearch = true;
        }
    }
}

function authorization() {
    $('#popupLogin').dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 300,
        position: 'center',
        open: function(event, ui) {
            $('#flMessage').hide();
            $('#flLogin,#flPassword').val('');
            $('#flLogin').focus();
        },
        close: function(event, ui) {
        }
    });

    $('#popupRegister').dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 330,
        position: 'center',
        open: function(event, ui) {
            $('#frMessage,#frmName,#frmEmail,#frmPassword').hide();
            $('#frForm input[type=text],#frForm input[type=password]').val('').removeClass('F-error');
            $('#frName').focus();
        },
        close: function(event, ui) {
        }
    });

    $('#frForm').submit(function() {
        doRegistration();
        return false;
    });

    $('#flForm').submit(function() {
        doLogin();
        return false;
    });
}

function popupLogin() {
    $('#popupLogin').dialog('open');
    return false;
}

function popupRegister() {
    $('#popupRegister').dialog('open');
    return false;
}

function doRegistration() {
    var data = {
        'Action': '',
        'Name': $('#frName').val(),
        'Email': $('#frEmail').val(),
        'Password': $('#frPassword').val(),
        'Subscribe': $('#frSubscribe').is(':checked') ? '1' : '0'
    };

    $('#frMessage').fadeOut();
    $('#frForm .F-message').fadeOut();
    $('#frForm input[type=text],#frForm input[type=password]').removeClass('F-error');

    $.post('/accounts/', data, function(result) {
        if (result['status'] == 'OK') {
            $("#frMessage").html(result['message']);
            $("#frMessage").fadeIn('fast');
            $("#frForm").hide();
        } else {
            if (result['message']) {
                $("#frMessage").html(result['message']);
                $("#frMessage").fadeIn('fast');
            }
            if (result['fields']) {
                $.each(result['fields'], function(i, v) {
                    $("#frm" + i).html(v);
                    $("#frm" + i).fadeIn('fast');
                    $("#fr" + i).addClass('F-error');
                });
            }
        }
    }, 'json');
}

function doLogin() {
    var data = {
        'Action': 'login',
        'Login': $('#flLogin').val(),
        'Password': $('#flPassword').val(),
        'Remember': $('#flRemember').is(':checked') ? 1 : 0
    };

    $('#flMessage').fadeOut();

    $.post('/accounts/', data, function(result) {
        if (result['status'] == 'OK') {
            window.location.reload();
        } else {
            if (result['message']) {
                $("#flMessage").html(result['message']);
                $("#flMessage").fadeIn('fast');
            }
        }
    }, 'json');
}

