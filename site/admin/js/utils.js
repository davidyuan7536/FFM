function IsNumeric(input) {
    return (input - 0) == input && input.length > 0;
}

var imagePreview = function() {
    var xI = 30;
    var yI = -20;

    var xT = -5;
    var yT = 26;

    $(".Thumbnail").hover(function(e) {
        this.t = this.title;
        this.title = "";
        var c = (this.t != "") ? "<br/>" + this.t : "";
        $("body").append("<div id='preview'><img src='" + this.src + "' alt='' />" + c + "</div>");
        $("#preview")
                .css("top", (e.pageY + yI) + "px")
                .css("left", (e.pageX + xI) + "px")
                .height(130)
                .fadeIn("fast");
    },
            function() {
                this.title = this.t;
                $("#preview").remove();
            });
    $(".Thumbnail").mousemove(function(e) {
        $("#preview")
                .css("top", (e.pageY + yI) + "px")
                .css("left", (e.pageX + xI) + "px");
    });

    $(".Trunc").hover(function(e) {
        var c = $(this).text();
        $("body").append("<div id='tooltip'>" + c.replace(/\&/gi, '&amp;').replace(/\</gi, '&lt;').replace(/\n/gi, '<br />') + "</div>");
        $("#tooltip")
                .css("top", (e.pageY + yT) + "px")
                .css("left", (e.pageX + xT) + "px")
                .fadeIn("fast");
    },
            function() {
                $("#tooltip").remove();
            });
    $(".Trunc").mousemove(function(e) {
        $("#tooltip")
                .css("top", (e.pageY + yT) + "px")
                .css("left", (e.pageX + xT) + "px");
    });
};

$(function() {
    $.extend($.fn.disableTextSelect = function() {
        return this.each(function() {
            if ($.browser.mozilla) {
                $(this).css('MozUserSelect', 'none');
            } else if ($.browser.msie) {
                $(this).bind('selectstart', function() {
                    return false;
                });
            } else {
                $(this).mousedown(function() {
                    return false;
                });
            }
        });
    });
});

(function($) {

    $.fn.selectableOne = function(settings) {
        var selectedElement;
        var config = {filter: '*'};

        if (settings) $.extend(config, settings);

        this.each(function() {
            $(config.filter, this).click(selectElement);
        });

        function selectElement() {
            if (selectedElement) {
                selectedElement.removeClass('ui-selected');
            }
            selectedElement = $(this);
            selectedElement.addClass('ui-selected');
        }

        return this;
    };

    $.escape = function(text) {
        return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
    };
})(jQuery);

var global_formNavigate = true;
(function($){
    $.fn.FormNavigate = function(message) {
        window.onbeforeunload = confirmExit;
        function confirmExit( event ) {
            if (global_formNavigate == true) {  event.cancelBubble = true;  }  else  { return message;  }
        }
        $(this+ ":input[type=text], :input[type='textarea'], :input[type='password'], :input[type='radio'], :input[type='checkbox'], :input[type='file'], select").change(function(){
            global_formNavigate = false;
        });
		//to handle back button
		$(this+ ":input[type='textarea']").keyup(function(){
			global_formNavigate = false;
		});
        $(this+ ":submit").click(function(){
            global_formNavigate = true;
        });
    }
})(jQuery);

jQuery.cookie = function (key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")) {
        options = jQuery.extend({}, options);

        if (value === null) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '',
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) {
        return s;
    } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

var StateCookie = function(sc) {
    var COOKIE_NAME = sc ? sc : 'STATE_COOKIE';
    var options = { path: '/', expires: 10 };

    function setState(id, state) {
        var c = [];

        if ($.cookie(COOKIE_NAME)) {
            c = $.cookie(COOKIE_NAME).split(',');

            if (state) {
                var exists = false;
                $.each(c, function(i, v) {
                    if (v == id) {
                        exists = true;
                    }
                });
                if (!exists) {
                    c.push(id);
                }
            } else {
                $.each(c, function(i, v) {
                    if (v == id) {
                        c[i] = null;
                    }
                })
            }
        } else if (state) {
            c = [id];
        }

        $.cookie(COOKIE_NAME, c.join(','), options);
    }

    function getState(id) {
        var state = false;

        if ($.cookie(COOKIE_NAME)) {
            var c = $.cookie(COOKIE_NAME).split(',');

            $.each(c, function(i, v) {
                if (v == id) {
                    state = true;
                }
            });
        }

        return state;
    }

    return {
        setState: function(id, state) {
            return setState(id, state);
        },
        getState: function(id) {
            return getState(id);
        }
    }
};

