if ("undefined" != typeof wavesurfer) {
    var loaded = [], rangeloading = [], ua = navigator.userAgent.toLowerCase(), msie = ua.indexOf("trident"),
        msedge = ua.indexOf("edge"), playbar = function (e, a) {
            "use strict";
            0 === a ? jQuery(".jp-play-bar").width(e + "%") : jQuery(".jp-play-bar").stop().animate({
                width: e + "%"
            }, a, "linear")
        };

    function loadingWave(e, a) {
        "use strict";
        "loading" === a ? (jQuery(".wav-loader:eq(" + e + ")").html("loading"), jQuery(".wav > wav:eq(" + e + ")").css({
            opacity: .2
        })) : (jQuery(".wav-loader:eq(" + e + ")").empty(), jQuery(".wav > wav:eq(" + e + ")").css({
            opacity: 1
        }))
    }

    function playbarProcess(e, a) {
        "use strict";
        e > 0 && (playbar(100 * (e / a), 80), jQuery(".jp-current-time").html(convertTime(e)))
    }

    function convertTime(e) {
        "use strict";
        e = e && "number" == typeof e ? e : 0;
        var a = new Date(1e3 * e), o = a.getUTCHours(), r = a.getUTCMinutes() + 60 * o, u = a.getUTCSeconds(), t = "";
        return t += (r < 10 ? "0" + r : r) + ":", t += (u < 10 ? "0" + u : u) + ""
    }

    function changeVolume(e) {
        "use strict";
        jQuery.each(wavesurfer, function (a) {
            wavesurfer[a].setVolume(e)
        })
    }



    var volume = "0.8";


        jQuery(".jp-play").on("click", function () {
        var e = 0;
            jQuery.each(jQuery(".ojoo-button"), function (a) {
            jQuery(this).hasClass("active") && (e = a)
        }), jQuery(".ojoo-button:eq(" + e + ")").trigger("click")
    }),





        jQuery(".jp-pause").on("click", function () {
        jQuery(".ojoo-button.active").trigger("click")
    }), jQuery(".jp-next").on("click", function () {
        var e = 1;
        jQuery.each(jQuery(".ojoo-button"), function (a) {
            jQuery(this).hasClass("active") && (e = a + 1)
        }), jQuery(".ojoo-button:eq(" + e + ")").trigger("click")
    }), jQuery(".jp-previous").on("click", function () {
        var e = 0;
        jQuery.each(jQuery(".ojoo-button"), function (a) {
            jQuery(this).hasClass("active") && (e = a - 1)
        }), e >= 0 && jQuery(".ojoo-button:eq(" + e + ")").trigger("click")
    }), jQuery.each(wavesurfer, function (e) {
        var a = e + 1, o = jQuery("audio:eq(" + e + ")"), r = wavesurfer[e].params.backend;
        rangeloading[e] = !1, wavesurfer[e].setVolume(volume), wavesurfer[e].on("waveform-ready", function () {
            loadingWave(e, "stop"), loaded[e] = !0
        }), wavesurfer[e].on("ready", function () {
            wavesurfer[e].setVolume(volume), loadingWave(e, "stop"), loaded[e] = !0
        }), wavesurfer[e].on("seek", function () {
            var a = wavesurfer[e].getDuration();
            playbarProcess(wavesurfer[e].getCurrentTime(), a)
        }), wavesurfer[e].on("play", function () {
            if (jQuery(".ojoo-button:eq(" + e + ")").hasClass("active")) {
                var a = wavesurfer[e].getDuration();
                jQuery(".jp-progress").addClass("jp-active"), jQuery(".jp-duration").html(convertTime(a)), jQuery(".ojoo-audio-box:not(:eq(" + e + ")).active").removeClass("active"), jQuery(".ojoo-audio-box:eq(" + e + ")").addClass("active")
            } else
                wavesurfer[e].stop();



            jQuery(".jp-seek-bar").show(), (!0 === loaded[e] || msie > 0) && loadingWave(e, "stop")
        }), wavesurfer[e].on("audioprocess", function () {
            var a = wavesurfer[e].getDuration(), u = wavesurfer[e].getCurrentTime();
            playbarProcess(u, a), u > 0 && (loadingWave(e, "stop"), loaded[e] = !0), "MediaElement" === r && (rangeloading[e] || 3 !== o[0].readyState || 0 !== u || (loadingWave(e, "loading"), rangeloading[e] = !0), (!0 === rangeloading[e] && 3 === o[0].readyState && u > 0 || !0 === rangeloading[e] && 4 === o[0].readyState) && (loadingWave(e, "stop"), rangeloading[e] = !1))
        }), wavesurfer[e].on("finish", function () {
            playbar(0, 1), jQuery(".jp-progress").removeClass("jp-active"), jQuery(".jp-duration").empty(), jQuery(".jp-current-time").empty(), jQuery(".jp-play").show(), jQuery(".jp-pause").hide(), wavesurfer[e].stop(), wavesurfer[e].toggleInteraction(), jQuery(".ojoo-button").removeClass("ojoo-pause"), jQuery(".ojoo-audio-box:eq(" + e + ")").removeClass("active"), jQuery(".jp-title-content").empty(), wavesurfer[a] && (jQuery(".jp-autoplay").hasClass("on") && jQuery(".ojoo-button:eq(" + a + ")").trigger("click"), jQuery(".ojoo-button:eq(" + a + ")").addClass("active"))
        }), jQuery(window).resize(function () {
            wavesurfer[e].empty(), wavesurfer[e].drawBuffer()
        })
    }), jQuery("body").keydown(function (e) {
        jQuery("#edit-keys-ojoo").is(":focus") || (37 == e.keyCode ? jQuery(".jp-previous").trigger("click") : 39 == e.keyCode && jQuery(".jp-next").trigger("click"))
    }), jQuery(".ojoo-button").on("click", function () {
        jQuery(this).hasClass("active") || (playbar(0, 0), jQuery(this).addClass("active"));
        var e = jQuery(".ojoo-button").index(this);
        jQuery.each(jQuery(".ojoo-button"), function (a) {
            a !== e || wavesurfer[a].params.interact || wavesurfer[a].toggleInteraction(), a !== e && ((jQuery(this).hasClass("active") || jQuery(this).hasClass("ojoo-pause") || wavesurfer[a].params.interact) && (wavesurfer[a].stop(), loadingWave(a, "stop")), jQuery(this).hasClass("ojoo-pause") && jQuery(this).removeClass("ojoo-pause"), wavesurfer[a].params.interact && wavesurfer[a].toggleInteraction(), jQuery(this).removeClass("active"))
        }),

            jQuery(this).hasClass("ojoo-pause") ? (jQuery(this).removeClass("ojoo-pause"), wavesurfer[e].pause(), jQuery(".jp-play").show(),

                jQuery(".jp-pause").hide()) : (jQuery(this).addClass("ojoo-pause"), jQuery(".jp-play").hide(), jQuery(".jp-pause").show(),

                jQuery(".jp-seek-bar").hide(), loaded[e] || loadingWave(e, "loading"), wavesurfer[e].play())
    }),

        jQuery(".jp-controls a").on("touchstart", function () {
        jQuery(this).removeClass("touch-out").addClass("touch-in")
    }),


        jQuery(".jp-controls a").on("touchend", function () {
        jQuery(this).removeClass("touch-in").addClass("touch-out")
    })
}



