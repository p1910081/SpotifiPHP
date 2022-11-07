<?php


use App\Entity\Artist;
use App\Autoloader;

require 'Auth-Spotify.php';
include_once 'Autoloader.php';

Autoloader::register();


if (isset($_POST['nom'])) {
    $_SESSION["nom"] = $_POST['nom'];
} else {
    $_SESSION["nom"] = "orelsan";
    // Par defaut
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/search?q=" . $_SESSION["nom"] . "&type=artist");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$_SESSION["res"] = $result;
$arr = json_decode($result)->artists->items;
foreach ($arr as $element) {

    if(count($element->images) !== 0)
    {
        $artist = new Artist($element->id, $element->name, $element->images[0]->url, $element->followers->total, $element->external_urls->spotify);
    }
    else{
        $artist = new Artist($element->id, $element->name, "", $element->followers->total, $element->external_urls->spotify);
    }

}

curl_close($ch);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Recherche</title>
</head>
<body>
<form action="index.php" method="post">
    <p>Recherche d'acteur <input type="text" name="nom"/></p>
    <p><input type="submit" value="OK"></p>
</form>
<?php
echo $artist->display();
?>

<div style="display: flex; flex-wrap: wrap">

<?php

$artist->display();

?>


</body>
</html>