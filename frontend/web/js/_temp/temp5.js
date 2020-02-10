var wavesurfer = WaveSurfer.create({
    container: '#waveform',
    waveColor: 'white',
    progressColor: 'red',
    audioRate: 1,
    barWidth: 3,
    height: 250,
    pixelRatio:1
});
wavesurfer.load('http://ia902606.us.archive.org/35/items/shortpoetry_047_librivox/song_cjrg_teasdale_64kb.mp3');

wavesurfer.on('ready', function () {
    var timeline = Object.create(WaveSurfer.Timeline);

    timeline.init({
        wavesurfer: wavesurfer,
        container: '#waveform-timeline',
        primaryFontColor: '#fff',
        primaryColor: '#fff',
        secondaryColor: '#fff',
        secondaryFontColor: '#fff'
    });});

wavesurfer.on('audioprocess', function() {
    if(wavesurfer.isPlaying()) {
        var totalTime = wavesurfer.getDuration(),
            currentTime = wavesurfer.getCurrentTime(),
            remainingTime = totalTime - currentTime;

        document.getElementById('time-total').innerText = Math.round(totalTime * 1000);
        document.getElementById('time-current').innerText = Math.round(currentTime * 1000);
        document.getElementById('time-remaining').innerText = Math.round(remainingTime * 1000);
    }
});