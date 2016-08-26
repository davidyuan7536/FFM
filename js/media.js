var FlashPlayer = function(id, container, mp3, onReady) {
    var requiredFlashVersion = '9.0.0';
    var PLAYER_SWF = '/js/player.swf';
    var swf;

    var flashvars = {
        listener: 'Listener',
        interval: '200'
    };
    var params = {
        allowscriptaccess: 'always'
    };

    if ($.browser.msie) {
        var data = 'eval(args);';
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.setAttribute('event', 'FSCommand(command, args)');
        script.setAttribute('for', id);
        script.text = data;
        var div = document.createElement('div');
        div.innerHTML = '&nbsp;' + script.outerHTML;
        document.body.appendChild(div);
    }

    $('<div/>', {id: id}).appendTo(container);
    swfobject.embedSWF(
            PLAYER_SWF,
            id,
            '1',
            '1',
            requiredFlashVersion,
            false,
            flashvars,
            params,
            null,
            function(e) {
                onReady(e.success);
            });

    function getSwfObject() {
        swf = swfobject.getObjectById(id);
        swf.SetVariable('method:setVolume', '50');
        swf.SetVariable('method:setUrl', mp3);
    }

    return {
        actionPlay: function() {
            if (!swf) {
                getSwfObject();
            }
            swf.SetVariable('method:play', '');
            swf.SetVariable('enabled', 'true');
        },
        actionPause: function() {
            swf.SetVariable('method:pause', '');
            swf.SetVariable('enabled', 'false');
        },
        actionStop: function() {
            swf.SetVariable('method:stop', '');
            swf.SetVariable('enabled', 'false');
        },
        setPosition: function(ms) {
            swf.SetVariable('method:setPosition', ms);
        },
        setVolume: function(v) {
            swf.SetVariable('method:setVolume', v);
        }
    }
};

var EmbedPlayer = function(url, callback) {
    var enabled = false;
    var playing = false;
    var flashPlayer = FlashPlayer('FlashPlayer', 'body', url, onPlayerInit);

    function onPlayerInit(state) {
        enabled = state;
    }

    function doCallback() {
        callback(playing);
    }

    return {
        doPlay: function() {
            if (enabled) {
                if (playing) {
                    flashPlayer.actionPause();
                    playing = false;
                } else {
                    flashPlayer.actionPlay();
                    playing = true;
                }
                doCallback();
            }
        },
        doStop: function() {
            playing = false;
            doCallback();
        },
        seek: function(ms) {
            if (playing) {
                flashPlayer.setPosition(ms);
            }
        },
        volume: function(pc) {
            if (playing) {
                flashPlayer.setVolume(pc);
            }
        }
    }
};

var player;
var duration;

var Listener = {
    bytesTotal: 0,
    bytesLoaded: 0,
    bytesPercent: 0,
    position: 0,
    duration: 0,
    volume: 0,
    isPlaying: 'false',
    onInit: function() {
    },
    onUpdate: function() {
        var w = $('#Container').width();
        $('#VolumeSize').width(70 * Listener.volume / 100);
        $('#Loaded').width(w * Listener.bytesPercent / 100);
        $('#Progress').width(w * Listener.position / (1000 * duration));
        $('#TimeCurrent').text(Listener.getTime(Listener.position / 1000));
        $('#TimeLeft').text(Listener.getTime(Listener.position / 1000 - duration));
        if (Listener.isPlaying == 'false') {
            player.doStop();
        }
    },
    seek: function(e) {
        var w = $('#Container').width();
        var ms = e.offsetX * duration * 1000 / w;
        player.seek(ms);
    },
    seekVolume: function(e) {
        var pc = e.offsetX * 100 / 70; // $('#Volume').width()
        player.volume(pc);
    },
    getTime: function (seconds) {
        var sign = ((seconds < 0) ? '-' : '');
        seconds = Math.abs(seconds);
        var contentSeconds = Math.round(((seconds / 60) - Math.floor(seconds / 60)) * 60);
        var contentMinutes = Math.floor(seconds / 60);
        if (contentSeconds >= 60) {
            contentSeconds -= 60;
            contentMinutes++;
        }
        return sign + contentMinutes + (contentSeconds < 10 ? ':0' : ':') + contentSeconds;
    }
};

$(function() {
    if ($.browser.mozilla) {
        $('body').css('MozUserSelect', 'none');
    } else if ($.browser.msie) {
        $('body').bind('selectstart', function() {
            return false;
        });
    } else {
        $('body').mousedown(function() {
            return false;
        });
    }

    var play = new Image();
    play.src = '/i/player/button-play.png';
    var pause = new Image();
    pause.src = '/i/player/button-pause.png';

    function onAction(state) {
        if (state) {
            $('#Genres').css({borderBottom: '1px solid #f52b2b'});
            $('#Play').attr('src', pause.src);
        } else {
            $('#Genres').css({borderBottom: '1px solid #000'});
            $('#Play').attr('src', play.src);
        }
    }

    duration = $('#Duration').val();
    player = EmbedPlayer($('#url').val(), onAction);
    $('#Play').click(player.doPlay);
    $('#Container').click(Listener.seek);
    $('#Volume').click(Listener.seekVolume);

    var timeSeek = false;
    var volumeSeek = false;

    function resetSeek() {
        timeSeek = false;
        volumeSeek = false;
    }

    $('body').bind('mouseup', resetSeek).bind('mouseleave', resetSeek);
    $('#Container').mousedown(function(e) {
        timeSeek = true;
        Listener.seek(e);
    });
    $('#Container').mousemove(function(e) {
        if (timeSeek) {
            Listener.seek(e);
        }
    });
    $('#Volume').mousedown(function(e) {
        volumeSeek = true;
        Listener.seekVolume(e);
    });
    $('#Volume').mousemove(function(e) {
        if (volumeSeek) {
            Listener.seekVolume(e);
        }
    });

    $('#Share,#ShareClose').click(function() {
        $('#PlayerPanel,#SharePanel').slideToggle('fast');
    });

    $('.share').click(function() {
        var windowParams = {
            "width":       "800",
            "height":      "600",
            "top":         "0",
            "left":        "0",
            "directories": "no",
            "location":    "no",
            "resizeable":  "yes",
            "menubar":     "no",
            "toolbar":     "no",
            "scrollbars":  "yes",
            "status":      "no"
        };
        var i, paramString = "";
        for (i in windowParams) {
            if (windowParams.hasOwnProperty(i)) {
                paramString += (paramString === "") ? "" : ",";
                paramString += i + "=";

                if (windowParams[i] === true || windowParams[i] === false) {
                    paramString += (windowParams[i]) ? "yes" : "no";
                }
                else {
                    paramString += windowParams[i];
                }
            }
        }
        var popupWindow = window.open($(this).attr('href'), 'new_window', paramString);
        popupWindow.focus();
        return false;
    });

    $('input[readonly]').click(function() {
        this.select();
    });
    if ($.browser.mozilla) {
        $('input[readonly]').css('MozUserSelect', '');
    } else if ($.browser.msie) {
        $('input[readonly]').bind('selectstart', function(event) {
            event.stopPropagation();
        });
    } else {
        $('input[readonly]').mousedown(function(event) {
            event.stopPropagation();
        });
    }
});