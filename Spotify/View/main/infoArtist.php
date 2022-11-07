<?php
use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Music;



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
