
var wavesurfer = WaveSurfer.create({
    container: '#waveform',
    scrollParent: true,
    barWidth: 1,
    normalize: true,




});


var link = document.getElementById('music').getAttribute('data-about');



wavesurfer.load(link);


wavesurfer.on('ready', function () {
    wavesurfer.pause();

});

