<?php
use App\Entity\Artist;
use App\Entity\Model;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/search?q=".$data."&type=artist");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$arr = json_decode($result)->artists->items;
var_dump($arr);
$artists = [];
foreach ($arr as $element) {

    if(isset($element->images))
    {
        $artist =  new Artist($element->id, $element->name, $element->images[0]->url, $element->followers->total, $element->external_urls->spotify,false);
        if(!$artist->find($element->id)){ $artist->create();}
        $artists[] = $artist;

    }
    else{
        $artist =  new Artist($element->id, $element->name, "", $element->followers->total, $element->external_urls->spotify,false);
        if(!$artist->find($element->id)){ $artist->create();}
        $artists[] = $artist;
    }

}

curl_close($ch);
//var_dump($artist);

foreach ($artists as $artist){
    echo '<form action="/artist/fav/'.$artist->getId().'" method="post"><button type="submit">Mise en fav</button></form>' ;
    echo "<a href='/Artist/info/".$artist->getId()."'>".$artist->display()."</a>";

}



