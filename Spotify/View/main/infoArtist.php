<?php
use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Music;



echo "Info d'artist<br>";
echo $artist->display() ;




foreach ($listResp as $tab){
    echo "Albume -> ";
    echo $tab[0]->display();
    echo "<br>";
    foreach ($tab[1] as $music){
        echo "music -> ";
        echo $music->display();
        echo '<form action="/artist/favMusic/'.$music->getId().'/'.$artist->getId().'" method="post"><button type="submit">Mise en fav</button></form>' ;
        echo "<br>";
    }
}
