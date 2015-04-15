<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Rhythmnastic</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <section class="container">
            <h3>Titre</h3>
            <h4>par <b>Auteur</b></h4>

            <div id="content">
                <h1><span>00:00</span> / <small>00:00</small></h1>
            </div>

            <div id="keys"></div>

            <div id="audio"></div>
        </section>

        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script>
            var arrows = {
                38: {
                    sign: "⬆",
                    name: "up"
                },
                40: {
                    sign: "⬇",
                    name: "bottom"
                },
                39: {
                    sign: "➡",
                    name: "right"
                },
                37: {
                    sign: "⬅",
                    name: "left"
                }
            };
            var partition = [];
            var trackID = (window.location.hash).replace('#/', '');
            var music = "https://api.soundcloud.com/tracks/"+ trackID +"/stream?client_id=YOUR_CLIENT_ID";
            var musicElement = $('<audio src="'+ music +'">');
            var startTime = 0;

            $('#audio').html(musicElement);
            musicElement = $('#audio audio').get(0);


            // On recupere les infos de la musique
            $.get('http://api.soundcloud.com/tracks/'+ trackID +'.json?client_id=YOUR_CLIENT_ID', function (data) {
                $('h3').html(data.title);
                $('h4 b').html(data.user.username);
            });


            // Quand la musique est fini
            musicElement.onended = function () {
                console.log(JSON.stringify(partition));
                $('#content').html('<textarea>'+ JSON.stringify(partition, null, 4) +'</textarea>');
            };

            // Quand la musique est chargee
            musicElement.onloadeddata = function () {
                musicElement.play();
                startTime = new Date().getTime();

                var totalTime = Math.floor(musicElement.duration);
                var totalTimeMinute = Math.floor(totalTime / 60);
                var totalTimeSeconds = totalTime - (totalTimeMinute * 60);

                totalTimeMinute = (totalTimeMinute > 9) ? totalTimeMinute : "0"+ totalTimeMinute;
                totalTimeSeconds = (totalTimeSeconds > 9) ? totalTimeSeconds : "0"+ totalTimeSeconds;

                $('h1 small').html(totalTimeMinute +":"+ totalTimeSeconds);

                setInterval(function () {
                    var currentTime = parseInt(musicElement.currentTime);
                    currentTimeMinute = Math.floor(currentTime / 60);
                    currentTimeSeconds = currentTime - (currentTimeMinute * 60);

                    currentTimeMinute = (currentTimeMinute > 9) ? currenTimeMinute : "0"+ currentTimeMinute;
                    currentTimeSeconds = (currentTimeSeconds > 9) ? currentTimeSeconds : "0"+ currentTimeSeconds;
                    currentTime = currentTimeMinute +":"+ currentTimeSeconds;

                    $('h1 span').html(currentTime);
                }, 500);
            };


            // Quand on clique sur les touches
            $(document).on('keyup', function (e) {
                var code = e.keyCode;

                if (!arrows[code]) return false;

                var arrow = arrows[code];
                var currentTime = musicElement.currentTime +"";
                currentTime = currentTime.split('.');
                currentTime = currentTime[0] +"."+ currentTime[1][0];
                var element = $('<div class="key">');

                element.html(arrow.sign);
                $('#keys').append(element);

                setTimeout(function () { element.addClass('active'); }, 100);
                setTimeout(function () { element.remove(); }, 600);

                var timestamp = new Date().getTime() - startTime;

                partition[partition.length] = {
                    timestamp: timestamp,
                    type: arrow.name
                };
            });
        </script>
    </body>
</html>
