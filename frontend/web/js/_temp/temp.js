
if(!wavesurfer) var wavesurfer=[];
//if(!loaded) var loaded=[];
var eq=wavesurfer.length;

var ua = navigator.userAgent.toLowerCase();
var msie = ua.indexOf("trident");
var msedge = ua.indexOf("edge");
var vivaldi = ua.indexOf("vivaldi");

var showProgress = function (percent) {
    $j("#wave218764 .wav-loader").html("loading " + percent + "%");
    $j("#wave218764 > wav").css({opacity:0.2});
};
var hideProgress = function () {
    $j("#wave218764 .wav-loader").empty();
    $j("#wave218764 > wav").css({opacity:1});
};

if (msie > 0) {
    if(!audiocontext) var audiocontext="";
} else {
    if(!audiocontext) var audiocontext = new (window.AudioContext || window.webkitAudioContext);
}

var backaudio="MediaElement";
var audiopreload="none";

if (msedge > 0 || vivaldi > 0) backaudio="WebAudio";

wavesurfer[eq] = WaveSurfer.create({
    audioContext: audiocontext,
    container: "#wave218764",
    waveColor: "#999",
    progressColor: "#00ffaf",
    cursorColor: "#999",
    height:52,
    mediaType:"audio",
    normalize:true,
    hideScrollbar:true,
    interact:false,
    scrollParent: false,
    backend: backaudio
});
wavesurfer[eq].load("", audiopreload); //audiopreload

wavesurfer[eq].on("loading", showProgress);
wavesurfer[eq].on("ready", hideProgress);
wavesurfer[eq].on("waveform-ready", hideProgress);
wavesurfer[eq].on("destroy", hideProgress);
wavesurfer[eq].on("error", hideProgress);
wavesurfer[eq].on("play", function () {
    $j(".jp-title-content").html("<div>Now playing: <span class='jp-title'>EG 2007 Mini Cooper Sidewalk Close Hood hydraulics and door latch</span></div>");
});

