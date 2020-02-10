(function ($) {



    $('.wave').each(function () {
        //Generate unic ud
        var id = '_' + Math.random().toString(36).substr(2, 9);
        var path = $(this).attr('data-path');

        //Set id to container
        $(this).find(".wave-container").attr("id", id);

        //Initialize WaveSurfer
        var wavesurfer = WaveSurfer.create({

            container: '#' + id,
            barWidth: 1,
            normalize: true,
            // partialRender: true
            // backend: 'MediaElement',
            // mediaType: 'audio',
            // pixelRatio: 1,



        });


        //Load audio file
        wavesurfer.load(path);


        wavesurfer.on('ready', function () {


            function convertToTime(secs) {
                secs = Math.round(secs);
                var hours = Math.floor(secs / (60 * 60));

                var divisor_for_minutes = secs % (60 * 60);
                var minutes = Math.floor(divisor_for_minutes / 60);

                var divisor_for_seconds = divisor_for_minutes % 60;
                var seconds = Math.ceil(divisor_for_seconds);

                var obj = {
                    "h": hours,
                    "m": minutes,
                    "s": seconds
                };
                return minutes + 'm ' + seconds;
            }


            var d1 = document.getElementById(id);

            d1.insertAdjacentHTML('beforebegin', '<div id="duration" style="float: right">' + convertToTime(wavesurfer.getDuration()) + '</div>');


        });


        //Add button event
        $(this).find("button").click(function () {
            wavesurfer.playPause();

        });


    });


}
(jQuery));