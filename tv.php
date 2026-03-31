<?php
session_start();

$channels = [
    [
        "name" => "France 24",
        "country" => "France",
        "logo" => "https://static.france24.com/meta_og_twcards/images/f24_general/logo_f24_externe.png",
        "url" => "https://static.france24.com/live/F24_FR_HI_HLS/live_tv.m3u8"
    ],
    [
        "name" => "TV5 Monde",
        "country" => "France",
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/4/4c/TV5Monde_logo_2016.png",
        "url" => "https://ott.tv5monde.com/Content/HLS/Live/channel(fr)/index.m3u8"
    ],
    [
        "name" => "TRT World",
        "country" => "Turquie",
        "logo" => "https://www.trtworld.com/images/logo-trt-world-social.png",
        "url" => "https://tv-trtworld.live.trt.com.tr/master_1080.m3u8"
    ]
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chaînes TV - YearFlix</title>
</head>
<body>
    <h1>📺 Chaînes TV</h1>

    <?php foreach ($channels as $c): ?>
        <div style="margin-bottom:20px;">
            <img src="<?= $c['logo'] ?>" width="150"><br>
            <strong><?= $c['name'] ?></strong> (<?= $c['country'] ?>)<br>
            <a href="watch_tv.php?url=<?= urlencode($c['url']) ?>">▶️ Regarder</a>
        </div>
    <?php endforeach; ?>
</body>
</html>
