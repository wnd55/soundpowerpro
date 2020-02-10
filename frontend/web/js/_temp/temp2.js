!function(t, e) {
    "function" == typeof define && define.amd ? define("wavesurfer", [], function() {
        return t.WaveSurfer = e()
    }) : "object" == typeof exports ? module.exports = e() : t.WaveSurfer = e()
}(this, function() {
    "use strict";
    var t = {
        defaultParams: {
            audioContext: null,
            audioRate: 1,
            autoCenter: !0,
            backend: "WebAudio",
            barHeight: 1,
            closeAudioContext: !1,
            container: null,
            cursorColor: "#333",
            cursorWidth: 1,
            dragSelection: !0,
            fillParent: !0,
            forceDecode: !1,
            height: 128,
            hideScrollbar: !1,
            interact: !0,
            loopSelection: !0,
            mediaContainer: null,
            mediaControls: !1,
            mediaType: "audio",
            minPxPerSec: 20,
            partialRender: !1,
            pixelRatio: window.devicePixelRatio || screen.deviceXDPI / screen.logicalXDPI,
            progressColor: "#555",
            normalize: !1,
            renderer: "MultiCanvas",
            scrollParent: !0,
            skipLength: 0,
            splitChannels: !1,
            waveColor: "#999"
        },
        init: function(e) {
            if (this.params = t.util.extend({}, this.defaultParams, e), this.container = "string" == typeof e.container ? document.querySelector(this.params.container) : this.params.container, !this.container)
                throw new Error("Container element not found");
            if (null == this.params.mediaContainer ? this.mediaContainer = this.container : "string" == typeof this.params.mediaContainer ? this.mediaContainer = document.querySelector(this.params.mediaContainer) : this.mediaContainer = this.params.mediaContainer, !this.mediaContainer)
                throw new Error("Media Container element not found");
            this.savedVolume = 0, this.isMuted=!1, this.tmpEvents = [], this.currentAjax = null, this.createDrawer(), this.createBackend(), this.createPeakCache(), this.isDestroyed=!1
        },
        createDrawer: function() {
            var e = this;
            this.drawer = Object.create(t.Drawer[this.params.renderer]), this.drawer.init(this.container, this.params), this.drawer.on("redraw", function() {
                e.drawBuffer(), e.drawer.progress(e.backend.getPlayedPercents())
            }), this.drawer.on("click", function(t, i) {
                setTimeout(function() {
                    e.seekTo(i)
                }, 0)
            }), this.drawer.on("scroll", function(t) {
                e.params.partialRender && e.drawBuffer(), e.fireEvent("scroll", t)
            })
        },
        createBackend: function() {
            var e = this;
            this.backend && this.backend.destroy(), "AudioElement" == this.params.backend && (this.params.backend = "MediaElement"), "WebAudio" != this.params.backend || t.WebAudio.supportsWebAudio() || (this.params.backend = "MediaElement"), this.backend = Object.create(t[this.params.backend]), this.backend.init(this.params), this.backend.on("finish", function() {
                e.fireEvent("finish")
            }), this.backend.on("play", function() {
                e.fireEvent("play")
            }), this.backend.on("pause", function() {
                e.fireEvent("pause")
            }), this.backend.on("audioprocess", function(t) {
                e.drawer.progress(e.backend.getPlayedPercents()), e.fireEvent("audioprocess", t)
            })
        },
        createPeakCache: function() {
            this.params.partialRender && (this.peakCache = Object.create(t.PeakCache), this.peakCache.init())
        },
        getDuration: function() {
            return this.backend.getDuration()
        },
        getCurrentTime: function() {
            return this.backend.getCurrentTime()
        },
        play: function(t, e) {
            this.fireEvent("interaction", this.play.bind(this, t, e)), this.backend.play(t, e)
        },
        pause: function() {
            this.backend.isPaused() || this.backend.pause()
        },
        playPause: function() {
            this.backend.isPaused() ? this.play() : this.pause()
        },
        isPlaying: function() {
            return !this.backend.isPaused()
        },
        skipBackward: function(t) {
            this.skip( - t||-this.params.skipLength)
        },
        skipForward: function(t) {
            this.skip(t || this.params.skipLength)
        },
        skip: function(t) {
            var e = this.getCurrentTime() || 0, i = this.getDuration() || 1;
            e = Math.max(0, Math.min(i, e + (t || 0))), this.seekAndCenter(e / i)
        },
        seekAndCenter: function(t) {
            this.seekTo(t), this.drawer.recenter(t)
        },
        seekTo: function(t) {
            this.fireEvent("interaction", this.seekTo.bind(this, t));
            var e = this.backend.isPaused();
            e || this.backend.pause();
            var i = this.params.scrollParent;
            this.params.scrollParent=!1, this.backend.seekTo(t * this.getDuration()), this.drawer.progress(this.backend.getPlayedPercents()), e || this.backend.play(), this.params.scrollParent = i, this.fireEvent("seek", t)
        },
        stop: function() {
            this.pause(), this.seekTo(0), this.drawer.progress(0)
        },
        setVolume: function(t) {
            this.backend.setVolume(t)
        },
        getVolume: function() {
            return this.backend.getVolume()
        },
        setPlaybackRate: function(t) {
            this.backend.setPlaybackRate(t)
        },
        getPlaybackRate: function() {
            return this.backend.getPlaybackRate()
        },
        toggleMute: function() {
            this.setMute(!this.isMuted)
        },
        setMute: function(t) {
            t !== this.isMuted && (t ? (this.savedVolume = this.backend.getVolume(), this.backend.setVolume(0), this.isMuted=!0) : (this.backend.setVolume(this.savedVolume), this.isMuted=!1))
        },
        getMute: function() {
            return this.isMuted
        },
        getFilters: function() {
            return this.backend.filters || []
        },
        toggleScroll: function() {
            this.params.scrollParent=!this.params.scrollParent, this.drawBuffer()
        },
        toggleInteraction: function() {
            this.params.interact=!this.params.interact
        },
        drawBuffer: function() {
            var t = Math.round(this.getDuration() * this.params.minPxPerSec * this.params.pixelRatio), e = this.drawer.getWidth(), i = t, a = this.drawer.getScrollX(), s = Math.min(a + e, i);
            if (this.params.fillParent && (!this.params.scrollParent || t < e) && (a = 0, s = i = e), this.params.partialRender)
                for (var r = this.peakCache.addRangeToPeakCache(i, a, i), n = 0; n < r.length; n++) {
                    o = this.backend.getPeaks(i, r[n][0], r[n][1]);
                    this.drawer.drawPeaks(o, i, r[n][0], r[n][1])
                } else {
                a = 0, s = i;
                var o = this.backend.getPeaks(i, a, s);
                this.drawer.drawPeaks(o, i, a, s)
            }
            this.fireEvent("redraw", o, i)
        },
        zoom: function(t) {
            this.params.minPxPerSec = t, this.params.scrollParent=!0, this.drawBuffer(), this.drawer.progress(this.backend.getPlayedPercents()), this.drawer.recenter(this.getCurrentTime() / this.getDuration()), this.fireEvent("zoom", t)
        },
        loadArrayBuffer: function(t) {
            this.decodeArrayBuffer(t, function(t) {
                this.isDestroyed || this.loadDecodedBuffer(t)
            }.bind(this))
        },
        loadDecodedBuffer: function(t) {
            this.backend.load(t), this.drawBuffer(), this.fireEvent("ready")
        },
        loadBlob: function(t) {
            var e = this, i = new FileReader;
            i.addEventListener("progress", function(t) {
                e.onProgress(t)
            }), i.addEventListener("load", function(t) {
                e.loadArrayBuffer(t.target.result)
            }), i.addEventListener("error", function() {
                e.fireEvent("error", "Error reading file")
            }), i.readAsArrayBuffer(t), this.empty()
        },
        load: function(t, e, i) {
            switch (this.empty(), this.isMuted=!1, this.params.backend) {
                case"WebAudio":
                    return this.loadBuffer(t, e);
                case"MediaElement":
                    return this.loadMediaElement(t, e, i)
            }
        },
        loadBuffer: function(t, e) {
            var i = function(e) {
                return e && this.tmpEvents.push(this.once("ready", e)), this.getArrayBuffer(t, this.loadArrayBuffer.bind(this))
            }.bind(this);
            if (!e)
                return i();
            this.backend.setPeaks(e), this.drawBuffer(), this.tmpEvents.push(this.once("interaction", i))
        },
        loadMediaElement: function(t, e, i) {
            var a = t;
            if ("string" == typeof t)
                this.backend.load(a, this.mediaContainer, e, i);
            else {
                var s = t;
                this.backend.loadElt(s, e), a = s.src
            }
            this.tmpEvents.push(this.backend.once("canplay", function() {
                this.drawBuffer(), this.fireEvent("ready")
            }.bind(this)), this.backend.once("error", function(t) {
                this.fireEvent("error", t)
            }.bind(this))), e && this.backend.setPeaks(e), e&&!this.params.forceDecode ||!this.backend.supportsWebAudio() || this.getArrayBuffer(a, function(t) {
                this.decodeArrayBuffer(t, function(t) {
                    this.backend.buffer = t, this.backend.setPeaks(null), this.drawBuffer(), this.fireEvent("waveform-ready")
                }.bind(this))
            }.bind(this))
        },
        decodeArrayBuffer: function(t, e) {
            this.arraybuffer = t, this.backend.decodeArrayBuffer(t, function(i) {
                this.isDestroyed || this.arraybuffer != t || (e(i), this.arraybuffer = null)
            }.bind(this), this.fireEvent.bind(this, "error", "Error decoding audiobuffer"))
        },
        getArrayBuffer: function(e, i) {
            var a = this, s = t.util.ajax({
                url: e,
                responseType: "arraybuffer"
            });
            return this.currentAjax = s, this.tmpEvents.push(s.on("progress", function(t) {
                a.onProgress(t)
            }), s.on("success", function(t, e) {
                i(t), a.currentAjax = null
            }), s.on("error", function(t) {
                a.fireEvent("error", "XHR error: " + t.target.statusText), a.currentAjax = null
            })), s
        },
        onProgress: function(t) {
            if (t.lengthComputable)
                var e = t.loaded / t.total;
            else
                e = t.loaded / (t.loaded + 1e6);
            this.fireEvent("loading", Math.round(100 * e), t.target)
        },
        exportPCM: function(t, e, i) {
            t = t || 1024, e = e || 1e4, i = i ||!1;
            var a = this.backend.getPeaks(t, e), s = [].map.call(a, function(t) {
                return Math.round(t * e) / e
            }), r = JSON.stringify(s);
            return i || window.open("data:application/json;charset=utf-8," + encodeURIComponent(r)), r
        },
        exportImage: function(t, e) {
            return t || (t = "image/png"), e || (e = 1), this.drawer.getImage(t, e)
        },
        cancelAjax: function() {
            this.currentAjax && (this.currentAjax.xhr.abort(), this.currentAjax = null)
        },
        clearTmpEvents: function() {
            this.tmpEvents.forEach(function(t) {
                t.un()
            })
        },
        empty: function() {
            this.backend.isPaused() || (this.stop(), this.backend.disconnectSource()), this.cancelAjax(), this.clearTmpEvents(), this.drawer.progress(0), this.drawer.setWidth(0), this.drawer.drawPeaks({
                length: this.drawer.getWidth()
            }, 0)
        },
        destroy: function() {
            this.fireEvent("destroy"), this.cancelAjax(), this.clearTmpEvents(), this.unAll(), this.backend.destroy(), this.drawer.destroy(), this.isDestroyed=!0
        }
    };
    return t.create = function(e) {
        var i = Object.create(t);
        return i.init(e), i
    }, t.util = {
        extend: function(t) {
            return Array.prototype.slice.call(arguments, 1).forEach(function(e) {
                Object.keys(e).forEach(function(i) {
                    t[i] = e[i]
                })
            }), t
        },
        debounce: function(t, e, i) {
            var a, s, r, n = function() {
                r = null, i || t.apply(s, a)
            };
            return function() {
                s = this, a = arguments;
                var o = i&&!r;
                clearTimeout(r), (r = setTimeout(n, e)) || (r = setTimeout(n, e)), o && t.apply(s, a)
            }
        },
        min: function(t) {
            var e = 1 / 0;
            for (var i in t)
                t[i] < e && (e = t[i]);
            return e
        },
        max: function(t) {
            var e =- 1 / 0;
            for (var i in t)
                t[i] > e && (e = t[i]);
            return e
        },
        getId: function() {
            return "wavesurfer_" + Math.random().toString(32).substring(2)
        },
        ajax: function(e) {
            var i = Object.create(t.Observer), a = new XMLHttpRequest, s=!1;
            return a.open(e.method || "GET", e.url, !0), a.responseType = e.responseType || "json", a.addEventListener("progress", function(t) {
                i.fireEvent("progress", t), t.lengthComputable && t.loaded == t.total && (s=!0)
            }), a.addEventListener("load", function(t) {
                s || i.fireEvent("progress", t), i.fireEvent("load", t), 200 == a.status || 206 == a.status ? i.fireEvent("success", a.response, t) : i.fireEvent("error", t)
            }), a.addEventListener("error", function(t) {
                i.fireEvent("error", t)
            }), a.send(), i.xhr = a, i
        }
    }, t.Observer = {
        on: function(t, e) {
            this.handlers || (this.handlers = {});
            var i = this.handlers[t];
            return i || (i = this.handlers[t] = []), i.push(e), {
                name: t,
                callback: e,
                un: this.un.bind(this, t, e)
            }
        },
        un: function(t, e) {
            if (this.handlers) {
                var i = this.handlers[t];
                if (i)
                    if (e)
                        for (var a = i.length - 1; a >= 0; a--)
                            i[a] == e && i.splice(a, 1);
                    else
                        i.length = 0
            }
        },
        unAll: function() {
            this.handlers = null
        },
        once: function(t, e) {
            var i = this, a = function() {
                e.apply(this, arguments), setTimeout(function() {
                    i.un(t, a)
                }, 0)
            };
            return this.on(t, a)
        },
        fireEvent: function(t) {
            if (this.handlers) {
                var e = this.handlers[t], i = Array.prototype.slice.call(arguments, 1);
                e && e.forEach(function(t) {
                    t.apply(null, i)
                })
            }
        }
    }, t.util.extend(t, t.Observer), t.WebAudio = {
        scriptBufferSize: 256,
        PLAYING_STATE: 0,
        PAUSED_STATE: 1,
        FINISHED_STATE: 2,
        supportsWebAudio: function() {
            return !(!window.AudioContext&&!window.webkitAudioContext)
        },
        getAudioContext: function() {
            return t.WebAudio.audioContext || (t.WebAudio.audioContext = new (window.AudioContext || window.webkitAudioContext)), t.WebAudio.audioContext
        },
        getOfflineAudioContext: function(e) {
            return t.WebAudio.offlineAudioContext || (t.WebAudio.offlineAudioContext = new (window.OfflineAudioContext || window.webkitOfflineAudioContext)(1, 2, e)), t.WebAudio.offlineAudioContext
        },
        init: function(e) {
            this.params = e, this.ac = e.audioContext || this.getAudioContext(), this.lastPlay = this.ac.currentTime, this.startPosition = 0, this.scheduledPause = null, this.states = [Object.create(t.WebAudio.state.playing), Object.create(t.WebAudio.state.paused), Object.create(t.WebAudio.state.finished)], this.createVolumeNode(), this.createScriptNode(), this.createAnalyserNode(), this.setState(this.PAUSED_STATE), this.setPlaybackRate(this.params.audioRate), this.setLength(0)
        },
        disconnectFilters: function() {
            this.filters && (this.filters.forEach(function(t) {
                t && t.disconnect()
            }), this.filters = null, this.analyser.connect(this.gainNode))
        },
        setState: function(t) {
            this.state !== this.states[t] && (this.state = this.states[t], this.state.init.call(this))
        },
        setFilter: function() {
            this.setFilters([].slice.call(arguments))
        },
        setFilters: function(t) {
            this.disconnectFilters(), t && t.length && (this.filters = t, this.analyser.disconnect(), t.reduce(function(t, e) {
                return t.connect(e), e
            }, this.analyser).connect(this.gainNode))
        },
        createScriptNode: function() {
            this.ac.createScriptProcessor ? this.scriptNode = this.ac.createScriptProcessor(this.scriptBufferSize) : this.scriptNode = this.ac.createJavaScriptNode(this.scriptBufferSize), this.scriptNode.connect(this.ac.destination)
        },
        addOnAudioProcess: function() {
            var t = this;
            this.scriptNode.onaudioprocess = function() {
                var e = t.getCurrentTime();
                e >= t.getDuration() ? (t.setState(t.FINISHED_STATE), t.fireEvent("pause")) : e >= t.scheduledPause ? t.pause() : t.state === t.states[t.PLAYING_STATE] && t.fireEvent("audioprocess", e)
            }
        },
        removeOnAudioProcess: function() {
            this.scriptNode.onaudioprocess = null
        },
        createAnalyserNode: function() {
            this.analyser = this.ac.createAnalyser(), this.analyser.connect(this.gainNode)
        },
        createVolumeNode: function() {
            this.ac.createGain ? this.gainNode = this.ac.createGain() : this.gainNode = this.ac.createGainNode(), this.gainNode.connect(this.ac.destination)
        },
        setVolume: function(t) {
            this.gainNode.gain.value = t
        },
        getVolume: function() {
            return this.gainNode.gain.value
        },
        decodeArrayBuffer: function(t, e, i) {
            this.offlineAc || (this.offlineAc = this.getOfflineAudioContext(this.ac ? this.ac.sampleRate : 44100)), this.offlineAc.decodeAudioData(t, function(t) {
                e(t)
            }.bind(this), i)
        },
        setPeaks: function(t) {
            this.peaks = t
        },
        setLength: function(t) {
            if (!this.mergedPeaks || t != 2 * this.mergedPeaks.length - 1 + 2) {
                this.splitPeaks = [], this.mergedPeaks = [];
                for (var e = this.buffer ? this.buffer.numberOfChannels : 1, i = 0; i < e; i++)
                    this.splitPeaks[i] = [], this.splitPeaks[i][2 * (t - 1)] = 0, this.splitPeaks[i][2 * (t - 1) + 1] = 0;
                this.mergedPeaks[2 * (t - 1)] = 0, this.mergedPeaks[2 * (t - 1) + 1] = 0
            }
        },
        getPeaks: function(t, e, i) {
            if (this.peaks)
                return this.peaks;
            this.setLength(t);
            for (var a = this.buffer.length / t, s=~~(a / 10) || 1, r = this.buffer.numberOfChannels, n = 0; n < r; n++)
                for (var o = this.splitPeaks[n], h = this.buffer.getChannelData(n), l = e; l <= i; l++) {
                    for (var c=~~(l * a), u=~~(c + a), d = 0, p = 0, f = c; f < u; f += s) {
                        var m = h[f];
                        m > p && (p = m), m < d && (d = m)
                    }
                    o[2 * l] = p, o[2 * l + 1] = d, (0 == n || p > this.mergedPeaks[2 * l]) && (this.mergedPeaks[2 * l] = p), (0 == n || d < this.mergedPeaks[2 * l + 1]) && (this.mergedPeaks[2 * l + 1] = d)
                }
            return this.params.splitChannels ? this.splitPeaks : this.mergedPeaks
        },
        getPlayedPercents: function() {
            return this.state.getPlayedPercents.call(this)
        },
        disconnectSource: function() {
            this.source && this.source.disconnect()
        },
        destroy: function() {
            this.isPaused() || this.pause(), this.unAll(), this.buffer = null, this.disconnectFilters(), this.disconnectSource(), this.gainNode.disconnect(), this.scriptNode.disconnect(), this.analyser.disconnect(), this.params.closeAudioContext && ("function" == typeof this.ac.close && "closed" != this.ac.state && this.ac.close(), this.ac = null, this.params.audioContext ? this.params.audioContext = null : t.WebAudio.audioContext = null, t.WebAudio.offlineAudioContext = null)
        },
        load: function(t) {
            this.startPosition = 0, this.lastPlay = this.ac.currentTime, this.buffer = t, this.createSource()
        },
        createSource: function() {
            this.disconnectSource(), this.source = this.ac.createBufferSource(), this.source.start = this.source.start || this.source.noteGrainOn, this.source.stop = this.source.stop || this.source.noteOff, this.source.playbackRate.value = this.playbackRate, this.source.buffer = this.buffer, this.source.connect(this.analyser)
        },
        isPaused: function() {
            return this.state !== this.states[this.PLAYING_STATE]
        },
        getDuration: function() {
            return this.buffer ? this.buffer.duration : 0
        },
        seekTo: function(t, e) {
            if (this.buffer)
                return this.scheduledPause = null, null == t && (t = this.getCurrentTime()) >= this.getDuration() && (t = 0), null == e && (e = this.getDuration()), this.startPosition = t, this.lastPlay = this.ac.currentTime, this.state === this.states[this.FINISHED_STATE] && this.setState(this.PAUSED_STATE), {
                    start: t,
                    end: e
                }
        },
        getPlayedTime: function() {
            return (this.ac.currentTime - this.lastPlay) * this.playbackRate
        },
        play: function(t, e) {
            if (this.buffer) {
                this.createSource();
                var i = this.seekTo(t, e);
                t = i.start, e = i.end, this.scheduledPause = e, this.source.start(0, t, e - t), "suspended" == this.ac.state && this.ac.resume && this.ac.resume(), this.setState(this.PLAYING_STATE), this.fireEvent("play")
            }
        },
        pause: function() {
            this.scheduledPause = null, this.startPosition += this.getPlayedTime(), this.source && this.source.stop(0), this.setState(this.PAUSED_STATE), this.fireEvent("pause")
        },
        getCurrentTime: function() {
            return this.state.getCurrentTime.call(this)
        },
        getPlaybackRate: function() {
            return this.playbackRate
        },
        setPlaybackRate: function(t) {
            t = t || 1, this.isPaused() ? this.playbackRate = t : (this.pause(), this.playbackRate = t, this.play())
        }
    }, t.WebAudio.state = {}, t.WebAudio.state.playing = {
        init: function() {
            this.addOnAudioProcess()
        },
        getPlayedPercents: function() {
            var t = this.getDuration();
            return this.getCurrentTime() / t || 0
        },
        getCurrentTime: function() {
            return this.startPosition + this.getPlayedTime()
        }
    }, t.WebAudio.state.paused = {
        init: function() {
            this.removeOnAudioProcess()
        },
        getPlayedPercents: function() {
            var t = this.getDuration();
            return this.getCurrentTime() / t || 0
        },
        getCurrentTime: function() {
            return this.startPosition
        }
    }, t.WebAudio.state.finished = {
        init: function() {
            this.removeOnAudioProcess(), this.fireEvent("finish")
        },
        getPlayedPercents: function() {
            return 1
        },
        getCurrentTime: function() {
            return this.getDuration()
        }
    }, t.util.extend(t.WebAudio, t.Observer), t.MediaElement = Object.create(t.WebAudio), t.util.extend(t.MediaElement, {
        init: function(t) {
            this.params = t, this.media = {
                currentTime: 0,
                duration: 0,
                paused: !0,
                playbackRate: 1,
                play: function() {},
                pause: function() {}
            }, this.mediaType = t.mediaType.toLowerCase(), this.elementPosition = t.elementPosition, this.setPlaybackRate(this.params.audioRate), this.createTimer()
        },
        createTimer: function() {
            var t = this, e = function() {
                t.isPaused() || (t.fireEvent("audioprocess", t.getCurrentTime()), (window.requestAnimationFrame || window.webkitRequestAnimationFrame)(e))
            };
            this.on("play", e)
        },
        load: function(t, e, i, a) {
            var s = document.createElement(this.mediaType);
            s.controls = this.params.mediaControls, s.autoplay = this.params.autoplay ||!1, s.preload = null == a ? "auto" : a, s.src = t, s.style.width = "100%";
            var r = e.querySelector(this.mediaType);
            r && e.removeChild(r), e.appendChild(s), this._load(s, i)
        },
        loadElt: function(t, e) {
            var i = t;
            i.controls = this.params.mediaControls, i.autoplay = this.params.autoplay ||!1, this._load(i, e)
        },
        _load: function(t, e) {
            var i = this;
            "function" == typeof t.load && t.load(), t.addEventListener("error", function() {
                i.fireEvent("error", "Error loading media element")
            }), t.addEventListener("canplay", function() {
                i.fireEvent("canplay")
            }), t.addEventListener("ended", function() {
                i.fireEvent("finish")
            }), this.media = t, this.peaks = e, this.onPlayEnd = null, this.buffer = null, this.setPlaybackRate(this.playbackRate)
        },
        isPaused: function() {
            return !this.media || this.media.paused
        },
        getDuration: function() {
            var t = (this.buffer || this.media).duration;
            return t >= 1 / 0 && (t = this.media.seekable.end(0)), t
        },
        getCurrentTime: function() {
            return this.media && this.media.currentTime
        },
        getPlayedPercents: function() {
            return this.getCurrentTime() / this.getDuration() || 0
        },
        getPlaybackRate: function() {
            return this.playbackRate || this.media.playbackRate
        },
        setPlaybackRate: function(t) {
            this.playbackRate = t || 1, this.media.playbackRate = this.playbackRate
        },
        seekTo: function(t) {
            null != t && (this.media.currentTime = t), this.clearPlayEnd()
        },
        play: function(t, e) {
            this.seekTo(t), this.media.play(), e && this.setPlayEnd(e), this.fireEvent("play")
        },
        pause: function() {
            this.media && this.media.pause(), this.clearPlayEnd(), this.fireEvent("pause")
        },
        setPlayEnd: function(t) {
            var e = this;
            this.onPlayEnd = function(i) {
                i >= t && (e.pause(), e.seekTo(t))
            }, this.on("audioprocess", this.onPlayEnd)
        },
        clearPlayEnd: function() {
            this.onPlayEnd && (this.un("audioprocess", this.onPlayEnd), this.onPlayEnd = null)
        },
        getPeaks: function(e, i, a) {
            return this.buffer ? t.WebAudio.getPeaks.call(this, e, i, a) : this.peaks || []
        },
        getVolume: function() {
            return this.media.volume
        },
        setVolume: function(t) {
            this.media.volume = t
        },
        destroy: function() {
            this.pause(), this.unAll(), this.media && this.media.parentNode && this.media.parentNode.removeChild(this.media), this.media = null
        }
    }), t.AudioElement = t.MediaElement, t.Drawer = {
        init: function(t, e) {
            this.container = t, this.params = e, this.width = 0, this.height = e.height * this.params.pixelRatio, this.lastPos = 0, this.initDrawer(e), this.createWrapper(), this.createElements()
        },
        createWrapper: function() {
            this.wrapper = this.container.appendChild(document.createElement("wave")), this.style(this.wrapper, {
                display: "block",
                position: "relative",
                userSelect: "none",
                webkitUserSelect: "none",
                height: this.params.height + "px"
            }), (this.params.fillParent || this.params.scrollParent) && this.style(this.wrapper, {
                width: "100%",
                overflowX: this.params.hideScrollbar ? "hidden": "auto",
                overflowY: "hidden"
            }), this.setupWrapperEvents()
        },
        handleEvent: function(t, e) {
            !e && t.preventDefault();
            var i, a = t.targetTouches ? t.targetTouches[0].clientX: t.clientX, s = this.wrapper.getBoundingClientRect(), r = this.width, n = this.getWidth();
            return !this.params.fillParent && r < n ? (i = (a - s.left) * this.params.pixelRatio / r || 0) > 1 && (i = 1) : i = (a - s.left + this.wrapper.scrollLeft) / this.wrapper.scrollWidth || 0, i
        },
        setupWrapperEvents: function() {
            var t = this;
            this.wrapper.addEventListener("click", function(e) {
                var i = t.wrapper.offsetHeight - t.wrapper.clientHeight;
                if (0 != i) {
                    var a = t.wrapper.getBoundingClientRect();
                    if (e.clientY >= a.bottom - i)
                        return
                }
                t.params.interact && t.fireEvent("click", e, t.handleEvent(e))
            }), this.wrapper.addEventListener("scroll", function(e) {
                t.fireEvent("scroll", e)
            })
        },
        drawPeaks: function(t, e, i, a) {
            this.setWidth(e), this.params.barWidth ? this.drawBars(t, 0, i, 1e4) : this.drawWave(t, 0, i, 1e4)
        },
        style: function(t, e) {
            return Object.keys(e).forEach(function(i) {
                t.style[i] !== e[i] && (t.style[i] = e[i])
            }), t
        },
        resetScroll: function() {
            null !== this.wrapper && (this.wrapper.scrollLeft = 0)
        },
        recenter: function(t) {
            var e = this.wrapper.scrollWidth * t;
            this.recenterOnPosition(e, !0)
        },
        recenterOnPosition: function(t, e) {
            var i = this.wrapper.scrollLeft, a=~~(this.wrapper.clientWidth / 2), s = t - a, r = s - i, n = this.wrapper.scrollWidth - this.wrapper.clientWidth;
            if (0 != n) {
                if (!e&&-a <= r && r < a) {
                    s = i + (r = Math.max( - 5, Math.min(5, r)))
                }(s = Math.max(0, Math.min(n, s))) != i && (this.wrapper.scrollLeft = s)
            }
        },
        getScrollX: function() {
            return Math.round(this.wrapper.scrollLeft * this.params.pixelRatio)
        },
        getWidth: function() {
            return Math.round(this.container.clientWidth * this.params.pixelRatio)
        },
        setWidth: function(t) {
            this.width != t && (this.width = t, this.params.fillParent || this.params.scrollParent ? this.style(this.wrapper, {
                width: ""
            }) : this.style(this.wrapper, {
                width: ~~(this.width / this.params.pixelRatio) + "px"
            }), this.updateSize())
        },
        setHeight: function(t) {
            t != this.height && (this.height = t, this.style(this.wrapper, {
                height: ~~(this.height / this.params.pixelRatio) + "px"
            }), this.updateSize())
        },
        progress: function(t) {
            var e = 1 / this.params.pixelRatio, i = Math.round(t * this.width) * e;
            if (i < this.lastPos || i - this.lastPos >= e) {
                if (this.lastPos = i, this.params.scrollParent && this.params.autoCenter) {
                    var a=~~(this.wrapper.scrollWidth * t);
                    this.recenterOnPosition(a)
                }
                this.updateProgress(i)
            }
        },
        destroy: function() {
            this.unAll(), this.wrapper && (this.container.removeChild(this.wrapper), this.wrapper = null)
        },
        initDrawer: function() {},
        createElements: function() {},
        updateSize: function() {},
        drawWave: function(t, e) {},
        clearWave: function() {},
        updateProgress: function(t) {}
    }, t.util.extend(t.Drawer, t.Observer), t.Drawer.Canvas = Object.create(t.Drawer), t.util.extend(t.Drawer.Canvas, {
        createElements: function() {
            var t = this.wrapper.appendChild(this.style(document.createElement("canvas"), {
                position: "absolute",
                zIndex: 1,
                left: 0,
                top: 0,
                bottom: 0
            }));
            if (this.waveCc = t.getContext("2d"), this.progressWave = this.wrapper.appendChild(this.style(document.createElement("wave"), {
                    position: "absolute",
                    zIndex: 2,
                    left: 0,
                    top: 0,
                    bottom: 0,
                    overflow: "hidden",
                    width: "0",
                    display: "none",
                    boxSizing: "border-box",
                    borderRightStyle: "solid",
                    borderRightWidth: this.params.cursorWidth + "px",
                    borderRightColor: this.params.cursorColor
                })), this.params.waveColor != this.params.progressColor) {
                var e = this.progressWave.appendChild(document.createElement("canvas"));
                this.progressCc = e.getContext("2d")
            }
        },
        updateSize: function() {
            var t = Math.round(this.width / this.params.pixelRatio);
            this.waveCc.canvas.width = this.width, this.waveCc.canvas.height = this.height, this.style(this.waveCc.canvas, {
                width: t + "px"
            }), this.style(this.progressWave, {
                display: "block"
            }), this.progressCc && (this.progressCc.canvas.width = this.width, this.progressCc.canvas.height = this.height, this.style(this.progressCc.canvas, {
                width: t + "px"
            })), this.clearWave()
        },
        clearWave: function() {
            this.waveCc.clearRect(0, 0, this.width, this.height), this.progressCc && this.progressCc.clearRect(0, 0, this.width, this.height)
        },
        drawBars: function(e, i, a, s) {
            var r = this;
            if (e[0]instanceof Array) {
                var n = e;
                if (this.params.splitChannels)
                    return this.setHeight(n.length * this.params.height * this.params.pixelRatio), void n.forEach(function(t, e) {
                        r.drawBars(t, e, a, s)
                    });
                e = n[0]
            }
            var o = 1;
            [].some.call(e, function(t) {
                return t < 0
            }) && (o = 2);
            var h = .5 / this.params.pixelRatio, l = this.width, c = this.params.height * this.params.pixelRatio, u = c * i || 0, d = c / 2, p = e.length / o, f = this.params.barWidth * this.params.pixelRatio, m = Math.max(this.params.pixelRatio, ~~(f / 2)), v = f + m, g = 1 / this.params.barHeight;
            if (this.params.normalize) {
                var w = t.util.max(e), C = t.util.min(e);
                g =- C > w?-C : w
            }
            var y = p / l;
            this.waveCc.fillStyle = this.params.waveColor, this.progressCc && (this.progressCc.fillStyle = this.params.progressColor), [this.waveCc, this.progressCc].forEach(function(t) {
                if (t)
                    for (var i = a / y; i < s / y; i += v) {
                        var r = e[Math.floor(i * y * o)] || 0, n = Math.round(r / g * d);
                        t.fillRect(i + h, d - n + u, f + h, 2 * n)
                    }
            }, this)
        },
        drawWave: function(e, i, a, s) {
            var r = this;
            if (e[0]instanceof Array) {
                var n = e;
                if (this.params.splitChannels)
                    return this.setHeight(n.length * this.params.height * this.params.pixelRatio), void n.forEach(function(t, e) {
                        r.drawWave(t, e, a, s)
                    });
                e = n[0]
            }
            if (![].some.call(e, function(t) {
                    return t < 0
                })) {
                for (var o = [], h = 0, l = e.length; h < l; h++)
                    o[2 * h] = e[h], o[2 * h + 1] =- e[h];
                e = o
            }
            var c = .5 / this.params.pixelRatio, u = this.params.height * this.params.pixelRatio, d = u * i || 0, p = u / 2, f=~~(e.length / 2), m = 1;
            this.params.fillParent && this.width != f && (m = this.width / f);
            var v = 1 / this.params.barHeight;
            if (this.params.normalize) {
                var g = t.util.max(e), w = t.util.min(e);
                v =- w > g?-w : g
            }
            this.waveCc.fillStyle = this.params.waveColor, this.progressCc && (this.progressCc.fillStyle = this.params.progressColor), [this.waveCc, this.progressCc].forEach(function(t) {
                if (t) {
                    t.beginPath(), t.moveTo(a * m + c, p + d);
                    for (i = a; i < s; i++) {
                        r = Math.round(e[2 * i] / v * p);
                        t.lineTo(i * m + c, p - r + d)
                    }
                    for (var i = s - 1; i >= a; i--) {
                        var r = Math.round(e[2 * i + 1] / v * p);
                        t.lineTo(i * m + c, p - r + d)
                    }
                    t.closePath(), t.fill(), t.fillRect(0, p + d - c, this.width, c)
                }
            }, this)
        },
        updateProgress: function(t) {
            this.style(this.progressWave, {
                width: t + "px"
            })
        },
        getImage: function(t, e) {
            return this.waveCc.canvas.toDataURL(t, e)
        }
    }), t.Drawer.MultiCanvas = Object.create(t.Drawer), t.util.extend(t.Drawer.MultiCanvas, {
        initDrawer: function(t) {
            if (this.maxCanvasWidth = null != t.maxCanvasWidth ? t.maxCanvasWidth : 4e3, this.maxCanvasElementWidth = Math.round(this.maxCanvasWidth / this.params.pixelRatio), this.maxCanvasWidth <= 1)
                throw "maxCanvasWidth must be greater than 1.";
            if (this.maxCanvasWidth%2 == 1)
                throw "maxCanvasWidth must be an even number.";
            this.hasProgressCanvas = this.params.waveColor != this.params.progressColor, this.halfPixel = .5 / this.params.pixelRatio, this.canvases = []
        },
        createElements: function() {
            this.progressWave = this.wrapper.appendChild(this.style(document.createElement("wave"), {
                position: "absolute",
                zIndex: 2,
                left: 0,
                top: 0,
                bottom: 0,
                overflow: "hidden",
                width: "0",
                display: "none",
                boxSizing: "border-box",
                borderRightStyle: "solid",
                borderRightWidth: this.params.cursorWidth + "px",
                borderRightColor: this.params.cursorColor
            })), this.addCanvas()
        },
        updateSize: function() {
            for (var t = Math.round(this.width / this.params.pixelRatio), e = Math.ceil(t / this.maxCanvasElementWidth); this.canvases.length < e;)
                this.addCanvas();
            for (; this.canvases.length > e;)
                this.removeCanvas();
            for (var i in this.canvases)
                if (this.canvases[i].waveCtx) {
                    var a = this.maxCanvasWidth + 2 * Math.ceil(this.params.pixelRatio / 2);
                    i == this.canvases.length - 1 && (a = this.width - this.maxCanvasWidth * (this.canvases.length - 1)), this.updateDimensions(this.canvases[i], a, this.height), this.clearWaveForEntry(this.canvases[i])
                }
        },
        addCanvas: function() {
            var t = {}, e = this.maxCanvasElementWidth * this.canvases.length;
            t.wave = this.wrapper.appendChild(this.style(document.createElement("canvas"), {
                position: "absolute",
                zIndex: 1,
                left: e + "px",
                top: 0,
                bottom: 0,
                height: "100%"
            })), t.waveCtx = t.wave.getContext("2d"), this.hasProgressCanvas && (t.progress = this.progressWave.appendChild(this.style(document.createElement("canvas"), {
                position: "absolute",
                left: e + "px",
                top: 0,
                bottom: 0,
                height: "100%"
            })), t.progressCtx = t.progress.getContext("2d")), this.canvases.push(t)
        },
        removeCanvas: function() {
            var t = this.canvases.pop();
            t.wave.parentElement.removeChild(t.wave), this.hasProgressCanvas && t.progress.parentElement.removeChild(t.progress)
        },
        updateDimensions: function(t, e, i) {
            var a = Math.round(e / this.params.pixelRatio), s = Math.round(this.width / this.params.pixelRatio);
            t.start = t.waveCtx.canvas.offsetLeft / s || 0, t.end = t.start + a / s, t.waveCtx.canvas.width = e, t.waveCtx.canvas.height = i, this.style(t.waveCtx.canvas, {
                width: a + "px"
            }), this.style(this.progressWave, {
                display: "block"
            }), this.hasProgressCanvas && (t.progressCtx.canvas.width = e, t.progressCtx.canvas.height = i, this.style(t.progressCtx.canvas, {
                width: a + "px"
            }))
        },
        clearWave: function() {
            for (var t in this.canvases)
                this.clearWaveForEntry(this.canvases[t])
        },
        clearWaveForEntry: function(t) {
            t.waveCtx.clearRect(0, 0, t.waveCtx.canvas.width, t.waveCtx.canvas.height), this.hasProgressCanvas && t.progressCtx.clearRect(0, 0, t.progressCtx.canvas.width, t.progressCtx.canvas.height)
        },
        drawBars: function(e, i, a, s) {
            var r = this;
            if (e[0]instanceof Array) {
                var n = e;
                if (this.params.splitChannels)
                    return this.setHeight(n.length * this.params.height * this.params.pixelRatio), void n.forEach(function(t, e) {
                        r.drawBars(t, e, a, s)
                    });
                e = n[0]
            }
            var o = 1;
            [].some.call(e, function(t) {
                return t < 0
            }) && (o = 2);
            var h = this.width, l = this.params.height * this.params.pixelRatio, c = l * i || 0, u = l / 2, d = e.length / o, p = this.params.barWidth * this.params.pixelRatio, f = p + Math.max(this.params.pixelRatio, ~~(p / 2)), m = 1 / this.params.barHeight;
            if (this.params.normalize) {
                var v = t.util.max(e), g = t.util.min(e);
                m =- g > v?-g : v
            }
            for (var w = d / h, C = a / w; C < s / w; C += f) {
                var y = e[Math.floor(C * w * o)] || 0, P = Math.round(y / m * u);
                this.fillRect(C + this.halfPixel, u - P + c, p + this.halfPixel, 2 * P)
            }
        },
        drawWave: function(e, i, a, s) {
            var r = this;
            if (e[0]instanceof Array) {
                var n = e;
                if (this.params.splitChannels)
                    return this.setHeight(n.length * this.params.height * this.params.pixelRatio), void n.forEach(function(t, e) {
                        r.drawWave(t, e, a, s)
                    });
                e = n[0]
            }
            if (![].some.call(e, function(t) {
                    return t < 0
                })) {
                for (var o = [], h = 0, l = e.length; h < l; h++)
                    o[2 * h] = e[h], o[2 * h + 1] =- e[h];
                e = o
            }
            var c = this.params.height * this.params.pixelRatio, u = c * i || 0, d = c / 2, p = 1 / this.params.barHeight;
            if (this.params.normalize) {
                var f = t.util.max(e), m = t.util.min(e);
                p =- m > f?-m : f
            }
            this.drawLine(e, p, d, u, a, s), this.fillRect(0, d + u - this.halfPixel, this.width, this.halfPixel)
        },
        drawLine: function(t, e, i, a, s, r) {
            for (var n in this.canvases) {
                var o = this.canvases[n];
                this.setFillStyles(o), this.drawLineToContext(o, o.waveCtx, t, e, i, a, s, r), this.drawLineToContext(o, o.progressCtx, t, e, i, a, s, r)
            }
        },
        drawLineToContext: function(t, e, i, a, s, r, n, o) {
            if (e) {
                var h = i.length / 2, l = 1;
                this.params.fillParent && this.width != h && (l = this.width / h);
                var c = Math.round(h * t.start), u = Math.round(h * t.end);
                if (!(c > o || u < n)) {
                    var d = Math.max(c, n), p = Math.min(u, o);
                    e.beginPath(), e.moveTo((d - c) * l + this.halfPixel, s + r);
                    for (v = d; v < p; v++) {
                        var f = i[2 * v] || 0, m = Math.round(f / a * s);
                        e.lineTo((v - c) * l + this.halfPixel, s - m + r)
                    }
                    for (var v = p - 1; v >= d; v--) {
                        var f = i[2 * v + 1] || 0, m = Math.round(f / a * s);
                        e.lineTo((v - c) * l + this.halfPixel, s - m + r)
                    }
                    e.closePath(), e.fill()
                }
            }
        },
        fillRect: function(t, e, i, a) {
            for (var s = Math.floor(t / this.maxCanvasWidth), r = Math.min(Math.ceil((t + i) / this.maxCanvasWidth) + 1, this.canvases.length), n = s; n < r; n++) {
                var o = this.canvases[n], h = n * this.maxCanvasWidth, l = {
                    x1: Math.max(t, n * this.maxCanvasWidth),
                    y1: e,
                    x2: Math.min(t + i, n * this.maxCanvasWidth + o.waveCtx.canvas.width),
                    y2: e + a
                };
                l.x1 < l.x2 && (this.setFillStyles(o), this.fillRectToContext(o.waveCtx, l.x1 - h, l.y1, l.x2 - l.x1, l.y2 - l.y1), this.fillRectToContext(o.progressCtx, l.x1 - h, l.y1, l.x2 - l.x1, l.y2 - l.y1))
            }
        },
        fillRectToContext: function(t, e, i, a, s) {
            t && t.fillRect(e, i, a, s)
        },
        setFillStyles: function(t) {
            t.waveCtx.fillStyle = this.params.waveColor, this.hasProgressCanvas && (t.progressCtx.fillStyle = this.params.progressColor)
        },
        updateProgress: function(t) {
            this.style(this.progressWave, {
                width: t + "px"
            })
        },
        getImage: function(t, e) {
            var i = [];
            return this.canvases.forEach(function(a) {
                i.push(a.wave.toDataURL(t, e))
            }), i.length > 1 ? i : i[0]
        }
    }), t.Drawer.SplitWavePointPlot = Object.create(t.Drawer.Canvas), t.util.extend(t.Drawer.SplitWavePointPlot, {
        defaultPlotParams: {
            plotNormalizeTo: "whole",
            plotTimeStart: 0,
            plotMin: 0,
            plotMax: 1,
            plotColor: "#f63",
            plotProgressColor: "#F00",
            plotPointHeight: 2,
            plotPointWidth: 2,
            plotSeparator: !0,
            plotSeparatorColor: "black",
            plotRangeDisplay: !1,
            plotRangeUnits: "",
            plotRangePrecision: 4,
            plotRangeIgnoreOutliers: !1,
            plotRangeFontSize: 12,
            plotRangeFontType: "Ariel",
            waveDrawMedianLine: !0,
            plotFileDelimiter: "\t"
        },
        plotTimeStart: 0,
        plotTimeEnd: - 1,
        plotArrayLoaded: !1,
        plotArray: [],
        plotPoints: [],
        plotMin: 0,
        plotMax: 1,
        initDrawer: function(t) {
            var e = this;
            for (var i in this.defaultPlotParams)
                void 0 === this.params[i] && (this.params[i] = this.defaultPlotParams[i]);
            if (this.plotTimeStart = this.params.plotTimeStart, void 0 !== this.params.plotTimeEnd && (this.plotTimeEnd = this.params.plotTimeEnd), Array.isArray(t.plotArray)
            )this.plotArray = t.plotArray, this.plotArrayLoaded=!0;
            else {
                this.loadPlotArrayFromFile(t.plotFileUrl, function(t) {
                    e.plotArray = t, e.plotArrayLoaded=!0, e.fireEvent("plot_array_loaded")
                }, this.params.plotFileDelimiter)
            }
        },
        drawPeaks: function(t, e, i, a) {
            if (1 == this.plotArrayLoaded)
                this.setWidth(e), this.splitChannels=!0, this.params.height = this.params.height / 2, t[0]instanceof Array && (t = t[0]), this.params.barWidth ? this.drawBars(t, 1, i, a) : this.drawWave(t, 1, i, a), this.params.height = 2 * this.params.height, this.calculatePlots(), this.drawPlots();
            else {
                var s = this;
                s.on("plot-array-loaded", function() {
                    s.drawPeaks(t, e, i, a)
                })
            }
        },
        drawPlots: function() {
            var t = this.params.height * this.params.pixelRatio / 2, e = .5 / this.params.pixelRatio;
            this.waveCc.fillStyle = this.params.plotColor, this.progressCc && (this.progressCc.fillStyle = this.params.plotProgressColor);
            for (var i in this.plotPoints) {
                var a = parseInt(i), s = t - this.params.plotPointHeight - this.plotPoints[i] * (t - this.params.plotPointHeight), r = this.params.plotPointHeight;
                this.waveCc.fillRect(a, s, this.params.plotPointWidth, r), this.progressCc && this.progressCc.fillRect(a, s, this.params.plotPointWidth, r)
            }
            this.params.plotSeparator && (this.waveCc.fillStyle = this.params.plotSeparatorColor, this.waveCc.fillRect(0, t, this.width, e)), this.params.plotRangeDisplay && this.displayPlotRange()
        },
        displayPlotRange: function() {
            var t = this.params.plotRangeFontSize * this.params.pixelRatio, e = this.plotMax.toPrecision(this.params.plotRangePrecision) + " " + this.params.plotRangeUnits, i = this.plotMin.toPrecision(this.params.plotRangePrecision) + " " + this.params.plotRangeUnits;
            this.waveCc.font = t.toString() + "px " + this.params.plotRangeFontType, this.waveCc.fillText(e, 3, t), this.waveCc.fillText(i, 3, this.height / 2)
        },
        calculatePlots: function() {
            this.plotPoints = {}, this.calculatePlotTimeEnd();
            for (var t = [], e =- 1, i = 0, a = 99999999999999, s = 0, r = 99999999999999, n = this.plotTimeEnd - this.plotTimeStart, o = 0; o < this.plotArray.length; o++) {
                var h = this.plotArray[o];
                if (h.value > i && (i = h.value), h.value < a && (a = h.value), h.time >= this.plotTimeStart && h.time <= this.plotTimeEnd) {
                    var l = Math.round(this.width * (h.time - this.plotTimeStart) / n);
                    if (t.push(h.value), l !== e && t.length > 0) {
                        var c = this.avg(t);
                        c > s && (s = c), c < r && (r = c), this.plotPoints[e] = c, t = []
                    }
                    e = l
                }
            }
            "whole" == this.params.plotNormalizeTo ? (this.plotMin = a, this.plotMax = i) : "values" == this.params.plotNormalizeTo ? (this.plotMin = this.params.plotMin, this.plotMax = this.params.plotMax) : (this.plotMin = r, this.plotMax = s), this.normalizeValues()
        },
        normalizeValues: function() {
            var t = {};
            if ("none" !== this.params.plotNormalizeTo) {
                for (var e in this.plotPoints) {
                    var i = (this.plotPoints[e] - this.plotMin) / (this.plotMax - this.plotMin);
                    i > 1 ? this.params.plotRangeIgnoreOutliers || (t[e] = 1) : i < 0 ? this.params.plotRangeIgnoreOutliers || (t[e] = 0) : t[e] = i
                }
                this.plotPoints = t
            }
        },
        loadPlotArrayFromFile: function(e, i, a) {
            void 0 === a && (a = "\t");
            var s = [], r = {
                url: e,
                responseType: "text"
            };
            t.util.ajax(r).on("load", function(t) {
                if (200 == t.currentTarget.status) {
                    for (var e = t.currentTarget.responseText.split("\n"), r = 0; r < e.length; r++) {
                        var n = e[r].split(a);
                        2 == n.length && s.push({
                            time: parseFloat(n[0]),
                            value: parseFloat(n[1])
                        })
                    }
                    i(s)
                }
            })
        },
        calculatePlotTimeEnd: function() {
            void 0 !== this.params.plotTimeEnd ? this.plotTimeEnd = this.params.plotTimeEnd : this.plotTimeEnd = this.plotArray[this.plotArray.length - 1].time
        },
        avg: function(t) {
            return t.reduce(function(t, e) {
                return t + e
            }) / t.length
        }
    }), t.util.extend(t.Drawer.SplitWavePointPlot, t.Observer), t.PeakCache = {
        init: function() {
            this.clearPeakCache()
        },
        clearPeakCache: function() {
            this.peakCacheRanges = [], this.peakCacheLength =- 1
        },
        addRangeToPeakCache: function(t, e, i) {
            t != this.peakCacheLength && (this.clearPeakCache(), this.peakCacheLength = t);
            for (var a = [], s = 0; s < this.peakCacheRanges.length && this.peakCacheRanges[s] < e;)
                s++;
            for (s%2 == 0 && a.push(e); s < this.peakCacheRanges.length && this.peakCacheRanges[s] <= i;)
                a.push(this.peakCacheRanges[s]), s++;
            s%2 == 0 && a.push(i), a = a.filter(function(t, e, i) {
                return 0 == e ? t != i[e + 1] : e == i.length - 1 ? t != i[e - 1] : t != i[e - 1] && t != i[e + 1]
            }), this.peakCacheRanges = this.peakCacheRanges.concat(a), this.peakCacheRanges = this.peakCacheRanges.sort(function(t, e) {
                return t - e
            }).filter(function(t, e, i) {
                return 0 == e ? t != i[e + 1] : e == i.length - 1 ? t != i[e - 1] : t != i[e - 1] && t != i[e + 1]
            });
            var r = [];
            for (s = 0; s < a.length; s += 2)
                r.push([a[s], a[s + 1]]);
            return r
        },
        getCacheRanges: function() {
            for (var t = [], e = 0; e < this.peakCacheRanges.length; e += 2)
                t.push([this.peakCacheRanges[e], this.peakCacheRanges[e + 1]]);
            return t
        }
    }, function() {
        var e = function() {
            var e = document.querySelectorAll("wavesurfer");
            Array.prototype.forEach.call(e, function(e) {
                var i = t.util.extend({
                    container: e,
                    backend: "MediaElement",
                    mediaControls: !0
                }, e.dataset);
                e.style.display = "block";
                var a = t.create(i);
                if (e.dataset.peaks)
                    var s = JSON.parse(e.dataset.peaks);
                a.load(e.dataset.url, s)
            })
        };
        "complete" === document.readyState ? e() : window.addEventListener("load", e)
    }(), t
});

