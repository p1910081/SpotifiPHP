<?php
use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Music;



echo "Info d'artist<br>";
echo $artist->display() ;




foreach ($listResp as $tab){
    echo "<div style='
    display: flex;
    flex-wrap: wrap;'>";
    echo "<div style='
    display: flex;
    flex-direction: column;
    align-content: flex-start;
    align-items: center;'>";
    echo "Albume -> ";
    echo $tab[0]->display();
    //echo '<form action="/artist/favAlbum/'.$tab[0]->getId().'/'.$artist->getId().'" method="post"><button type="submit">Mise en fav de l.albume</button></form>' ;
    echo "<br>";
    echo "</div>";
    foreach ($tab[1] as $music){

        echo "<div style='
    display: flex;
    flex-direction: column;
    align-content: flex-start;
    align-items: center;'>";
        echo "music -> ";

        if( $tab[0]->find( $tab[0]->id) === false){
            echo $music->display();
            echo '<form action="/artist/favAlbum/'.$music->getId().'/'.$artist->getId().'" method="post"><button type="submit">Mise en fav de l.albume</button></form>' ;
        }else{
            echo '<form action="/artist/favAlbum/'.$music->getId().'/'.$artist->getId().'" method="post"><button type="submit">Supprimer le fav de l.albume</button></form>' ;
        }
        if( $music->find( $music->id) === false){
            echo '<form action="/artist/favMusic/'.$music->getId().'/'.$music->getName().'/'.$artist->getId().'" method="post"><button type="submit">Mise en fav de la musicque</button></form>' ;
        }else{
            echo '<form action="/artist/favMusic/'.$music->getId().'/'.$music->getName().'/'.$artist->getId().'" method="post"><button type="submit">Supprimer le fav de musique</button></form>' ;
        }
        echo "</div>";
        echo "<br>";

    }
    echo "</div>";
}
