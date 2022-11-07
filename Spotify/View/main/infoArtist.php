<?php
use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Music;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/".$data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result_artist = curl_exec($ch);

curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/".$data."/albums");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result_albums = curl_exec($ch);

curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/albums/".$data."/tracks");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result_tracks = curl_exec($ch);


$artist = json_decode($result_artist);
$albums = json_decode($result_albums)->items;



if(count($artist->images) !== 0)
{
    $artist =  new Artist($artist->id, $artist->name, $artist->images[0]->url, $artist->followers->total, $artist->external_urls->spotify);
}
else{
    $artist = new Artist($artist->id, $artist->name, "", $artist->followers->total, $artist->external_urls->spotify);
}



$listAlbums = [];
$listlistMusics = [];
foreach ($albums as $album){
    if(count($album->images) !== 0)
    {
        $listAlbums[] =  new Album($album->id, $album->name, $album->images[0]->url, $album->uri);
    }
    else{
        $listAlbums[] = new Album($album->id, $album->name, "", $album->uri);
    }

    curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/albums/".$album->id."/tracks");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result_tracks = curl_exec($ch);

    $tracks = json_decode($result_tracks)->items;

    foreach ($tracks as $musique){
        if(isset($musique?->images))
        {
            $listMusics[] =  new Music($musique->id, $musique->name, $musique->images[0]->url);
        }
        else{
            $listMusics[] = new Music($musique->id, $musique->name, "");
        }
    }
    $listlistMusics[] = $listMusics;

}

echo "Info d'artist<br>";
echo $artist->display() ;
foreach ($listAlbums as $album){
    echo "Albume -> ";
    echo $album->display();
    echo "<br>";
    foreach ($listlistMusics as $list){
        foreach ($list as $music){
            echo "musique -> ";
            echo $music->display();
            echo "<br>";
        }
    }

}
