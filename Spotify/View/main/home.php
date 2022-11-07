<?php
use App\Entity\Artist;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/search?q=".$data."&type=artist");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$arr = json_decode($result)->artists->items;
$artists = [];
foreach ($arr as $element) {

    if(count($element->images) !== 0)
    {
        $artists[] =  new Artist($element->id, $element->name, $element->images[0]->url, $element->followers->total, $element->external_urls->spotify);
    }
    else{
        $artists[] = new Artist($element->id, $element->name, "", $element->followers->total, $element->external_urls->spotify);
    }

}

curl_close($ch);
//var_dump($artist);

foreach ($artists as $artist){
    var_dump($artist);
    echo "<a href='/Artist/info/".$artist->getId()."'>".$artist->display()."</a>";
}



