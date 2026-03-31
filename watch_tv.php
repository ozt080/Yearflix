<?php
session_start();

if (!isset($_GET['url'])) {
    die("Aucune chaîne sélectionnée.");
}

$url = $_GET['url'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Regarder la chaîne - YearFlix</title>

    <!-- Import du lecteur HLS -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <h1>▶️ Lecture de la chaîne</h1>

    <video id="tvplayer" controls autoplay width="800"></video>

    <script>
        var video = document.getElementById('tvplayer');
        var streamURL = "<?php echo $url; ?>";

        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(streamURL);
            hls.attachMedia(video);
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = streamURL;
        } else {
            alert("Votre navigateur ne supporte pas HLS.");
        }
    </script>

    <p><a href="tv.php">⬅️ Retour aux chaînes</a></p>
</body>
</html>


