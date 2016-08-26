var GlobalPlayer = function() {
    var instances = [];
    var activePlayerID;
    var requiredFlashVersion = '9.0.0';

    function guid(length) {
        var result = [];
        for (var j = 0; j < length; j++) {
            result.push((((1 + Math.random()) * 0x10000) | 0).toString(16).substr(1));
        }
        return 'guid-' + result.join('');
    }

    function hasHtmlAudio() {
        var audio;
        try {
            audio = new Audio('');
        } catch(e) {
            audio = {};
        }
        return audio.canPlayType ? '' != audio.canPlayType('audio/mpeg') && 'no' != audio.canPlayType('audio/mpeg') : false;
    }

    var CoreHTML = function(id, container, mp3, onReady) {
        var waiting = true;
        var audio = new Audio();
        audio.id = id;
        audio.autobuffer = false;
        audio.controls = false;
        audio.volume = 0.5;
        container.append(audio);

        onReady(true);

        var playerControllerId = undefined;

        function playerOnProgressChange(lp, pt, tt) { // Called from HTML5 interval
            GlobalPlayer.Listener.bytesPercent = lp;
            GlobalPlayer.Listener.position = pt;
            GlobalPlayer.Listener.duration = tt;
            GlobalPlayer.Listener.volume = audio.volume * 100;
            GlobalPlayer.Listener.isPlaying = 'true';
            GlobalPlayer.Listener.onUpdate();
        }

        function playerController(override) { // The HTML5 interval function.
            var pt = 0, tt = 0, lp = 0;
            if (audio.readyState >= 1) {
                pt = audio.currentTime * 1000; // milliSeconds
                tt = audio.duration * 1000; // milliSeconds
                tt = isNaN(tt) ? 0 : tt; // Clean up duration in Firefox 3.5+
                if ((typeof audio.buffered == "object") && (audio.buffered.length > 0)) {
                    lp = 100 * audio.buffered.end(audio.buffered.length - 1) / audio.duration;
                } else {
                    lp = 100;
                }
            }

            if (audio.ended) {
                clearInterval(playerControllerId);
            }

            if (override) {
                playerOnProgressChange(lp, 0, tt);
            } else {
                playerOnProgressChange(lp, pt, tt);
            }
        }

        return {
            actionPlay: function() {
                if (waiting) {
                    audio.src = mp3;
                    audio.load();
                    waiting = false;
                }
                audio.play();
                clearInterval(playerControllerId);
                playerControllerId = window.setInterval(function() {
                    playerController(false);
                }, 200);
            },
            actionPause: function() {
                audio.pause();
                clearInterval(playerControllerId);
            },
            actionStop: function() {
                audio.pause();
                clearInterval(playerControllerId);
            },
            setPosition: function(p) {
                if ((typeof audio.buffered == "object") && (audio.buffered.length > 0)) {
                    audio.currentTime = p * audio.buffered.end(audio.buffered.length - 1) / 100;
                } else {
                    audio.currentTime = p * audio.duration / 100;
                }
            },
            setVolume: function(v) {
                audio.volume = v / 100;
            },
            setDuration: function(d) {
                return;
            }
        }
    };

    var CoreFlash = function(id, container, mp3, onReady) {
        var PLAYER_SWF = '/js/player.swf';
        var swf;
        var duration;

        var flashvars = {
            listener: 'GlobalPlayer.Listener',
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
                if (!swf) {
                    getSwfObject();
                }
                swf.SetVariable('method:pause', '');
                swf.SetVariable('enabled', 'false');
            },
            actionStop: function() {
                if (!swf) {
                    getSwfObject();
                }
                swf.SetVariable('method:stop', '');
                swf.SetVariable('enabled', 'false');
            },
            setPosition: function(p) {
                if (duration) {
                    var ms = p * duration / 100;
                    swf.SetVariable('method:setPosition', ms);
                }
            },
            setVolume: function(v) {
                swf.SetVariable('method:setVolume', v);
            },
            setDuration: function(d) {
                duration = d;
            }
        }
    };

    var Player = function(el, beforePlay) {
        var container = $(el);
        var link = container.find('.player-mp3');
        var name = container.find('.player-name');
        var mp3 = link.attr('href');
        var id = guid(2);
        var core, enabled = false, playing = false;
        var BUTTON = 'player-button';
        var BUTTON_PLAY = 'player-buttonPlay';
        var BUTTON_PAUSE = 'player-buttonPause';

        name.wrapInner('<div style="overflow: hidden; position: relative;"><span style="position: relative" /></div>');
        var inner = name.children().children();
        var offset = inner.outerWidth() - inner.parent().width();

        if (offset > 0) {
            container.mouseenter(function() {
                if (inner.queue().length == 0) {
                    var left = parseInt(inner.css('left')) || 0;
                    var size = offset + left;
                    inner.animate({left: '-=' + size + 'px'}, size * 30);
                }
            }).mouseleave(function() {
                if (inner.queue().length > 0) {
                    inner.stop();
                }
                var left = parseInt(inner.css('left')) || 0;
                inner.animate({left: 0}, -left * 30);
            });
            $('a', name).mouseenter(function() {
                inner.stop();
                inner.animate({left: 0});
            });
        }

        var button = $('<div/>', {
            'class': BUTTON + ' ' + BUTTON_PLAY,
            click: function() {
                if (playing) {
                    pause();
                } else {
                    play();
                }
            }
        });

        var progress = $('<div/>', {'class': 'player-progressbar'});
        var progressBar = $('<div><span></span></div>').appendTo(progress);

        var volume = $('<div/>', {
            'class': 'player-volume',
            html: '<span></span>'
        });

        progress.mouseup(function(e) {
            if (enabled) {
                var left = e.pageX - progress.offset().left;
                var p = left * 100 / progress.width();
                core.setPosition(p);
            }
        });

        volume.mouseup(function(e) {
            if (enabled) {
                var left = e.pageX - volume.offset().left;
                var v = Math.round(left * 100 / volume.width());
                core.setVolume(v);
            }
        });

        if (hasHtmlAudio()) {
            core = CoreHTML(id, container, mp3, onPlayerStart);
        } else if (swfobject.hasFlashPlayerVersion(requiredFlashVersion)) {
            core = CoreFlash(id, container, mp3, onPlayerStart);
        } else {
            onPlayerStart(false);
        }

        function checkButtonStyle() {
            if (playing) {
                button.removeClass(BUTTON_PLAY).addClass(BUTTON_PAUSE);
            } else {
                button.removeClass(BUTTON_PAUSE).addClass(BUTTON_PLAY);
            }
        }

        function onPlayerStart(state) {
            enabled = state;
            container.addClass(state ? 'player-enabled' : 'player-disabled');
            if (state) {
                button.appendTo(container);
                progress.appendTo(container);
                volume.appendTo(container);
            }
        }

        function redraw(state) {
            if (state.isPlaying != 'true') {
                playing = false;
                checkButtonStyle();
            }
            var leftLoaded = Math.round(192 * state.bytesPercent / 100) - 467;
            var leftPlayed = Math.round(192 * state.position / state.duration) - 467;
            var stateVolume = Math.round(52 * state.volume / 100) - 467;
            progress.css('background-position', leftLoaded + 'px -20px');
            progressBar.css('background-position', leftPlayed + 'px -10px');
            volume.css('background-position', stateVolume + 'px -20px');
            core.setDuration(state.duration);
        }

        function play() {
            if (enabled) {
                beforePlay(id);
                core.actionPlay();
                playing = true;
                checkButtonStyle();
            }
        }

        function pause() {
            if (enabled) {
                core.actionPause();
                playing = false;
                checkButtonStyle();
            }
        }

        function stop() {
            if (enabled) {
                core.actionStop();
                playing = false;
                checkButtonStyle();
            }
        }

        return {
            getId: function() {
                return id;
            },
            play: play,
            pause: pause,
            stop: stop,
            redraw: redraw
        }
    };

    function getPlayer(id) {
        return instances[id];
    }

    function getActivePlayer() {
        return getPlayer(activePlayerID);
    }

    function setActivePlayerId(id) {
        activePlayerID = id;
    }

    function beforePlay(id) {
        var activePlayer = getActivePlayer();
        if (activePlayer && activePlayer.getId() != id) {
            activePlayer.pause();
        }
        setActivePlayerId(id);
    }

    function disableSelection(target) {
        if (typeof target.onselectstart != "undefined") {
            target.onselectstart = function() {
                return false;
            }
        } else if (typeof target.style.MozUserSelect != "undefined") {
            target.style.MozUserSelect = "none";
        } else {
            target.onmousedown = function() {
                return false;
            }
        }
        target.style.cursor = "default";
    }

    return {
        Listener: {
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
                var activePlayer = getActivePlayer();
                if (activePlayer) {
                    activePlayer.redraw(this);
                }
            }
        },
        embed: function(el) {
            disableSelection(el);
            var p = new Player(el, beforePlay);
            instances[p.getId()] = p;
        }
    }
}();


var MiniListener = {
    bytesTotal: 0,
    bytesLoaded: 0,
    bytesPercent: 0,
    position: 0,
    duration: 0,
    volume: 0,
    isPlaying: 'false',
    name: '',
    player: null,
    guid: '',
    index: 0,
    chars: ['_', '-', '¯', '-'],
    onInit: function() {
    },
    onUpdate: function() {
        document.title = MiniListener.chars[MiniListener.index] + ' ' + MiniListener.name;
        MiniListener.index = MiniListener.index == 3 ? 0 : MiniListener.index + 1;
        if (MiniListener.isPlaying == 'false') {
            MiniListener.player.onStop();
        }
    }
};

$(function() {
    var MiniFlashPlayer = function(id, container, mp3, onReady) {
        var requiredFlashVersion = '9.0.0';
        var PLAYER_SWF = '/js/player.swf';
        var swf;

        var flashvars = {
            listener: 'MiniListener',
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

    function miniId(length) {
        var result = [];
        for (var j = 0; j < length; j++) {
            result.push((((1 + Math.random()) * 0x10000) | 0).toString(16).substr(1));
        }
        return 'MiniPlayer' + result.join('');
    }

    var MiniEmbedPlayer = function(url, name, callback) {
        var miniEnabled = false;
        var miniPlaying = false;
        var guid = miniId(2);
        var player = MiniFlashPlayer(guid, 'body', url, onPlayerInit);

        function onPlayerInit(state) {
            miniEnabled = state;
        }

        function doCallback() {
            callback(miniPlaying);
        }

        return {
            doAction: function(action) {
                if (action) {
                    player.actionPlay();
                } else {
                    player.actionPause();
                }
                miniPlaying = action;
                doCallback();
            },
            doPlay: function() {
                if (miniEnabled) {
                    if (MiniListener.guid == guid) {
                        this.doAction(!miniPlaying);
                    } else {
                        if (MiniListener.player && MiniListener.player.isPlaying()) {
                            MiniListener.player.doAction(false);
                        }
                        MiniListener.guid = guid;
                        MiniListener.player = this;
                        MiniListener.name = name;
                        this.doAction(true);
                    }
                }
            },
            onStop: function() {
                miniPlaying = false;
                doCallback();
            },
            seek: function(ms) {
                if (miniPlaying) {
                    player.setPosition(ms);
                }
            },
            volume: function(pc) {
                if (miniPlaying) {
                    player.setVolume(pc);
                }
            },
            isPlaying: function() {
                return miniPlaying;
            }
        }
    };

    $.fn.extend({
        miniPlayer: function(prefix) {
            var title = document.title;
            var cssPlaying = prefix + '-Playing';

            var MiniPlayer = function(el) {
                var container = $(el).click(onClick);

                var player = MiniEmbedPlayer(container.attr('url'), container.attr('title'), onAction);

                function onAction(state) {
                    if (state) {
                        container.addClass(cssPlaying);
                    } else {
                        container.removeClass(cssPlaying);
                        document.title = title;
                    }
                }

                function onClick() {
                    player.doPlay();
                }
            };

            return this.each(function() {
                new MiniPlayer(this);
            });
        }
    });
});

