<?php
namespace App\Controllers;
use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Music;

use App\Entity\Model;


class ArtistController extends Controller
{
    public function search()
    {
        $data = $_POST['data'];



        $this->render('main/home', compact("data"));
    }
    public function favArtist($id_artist, $data_init)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/".$id_artist);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_artist = curl_exec($ch);
        $artist = json_decode($result_artist);
        $artist =  new Artist($artist->id, $artist->name, $artist->images[0]->url, $artist->followers->total, $artist->external_urls->spotify);

        if($artist->find($artist->id) === false){ $artist->create();}
        else{
            $artist->delete($artist->id);
        }

        $this->render('main/home', compact("data_init"));
    }
    public function favAlbum($id_album, $id_artist)
    {
        $ch = curl_init();
        var_dump($id_album);
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/tracks/".$id_album);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_tracks = curl_exec($ch);
        $tracks = json_decode($result_tracks)->album;

        var_dump($tracks->id);
        $album =  new Album($tracks->id, $tracks->name, $tracks->images[0]->url, $tracks->external_urls->spotify);

        if($album->find($album->id) === false){ $album->create();}
        else{$album->delete($tracks->id);}

        header('Location: http://localhost:8000/Artist/info/'.$id_artist);
    }
    public function favMusic($id_music, $name_music, $id_artist)
    {
        var_dump($id_music);
        $music = new Music($id_music, $name_music,"");
        if($music->find($id_music) === false){ $music->create();}
        else{$music->delete($id_music);}

        header('Location: http://localhost:8000/Artist/info/'.$id_artist);
    }
    public function info($id_artist)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/".$id_artist);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_artist = curl_exec($ch);

        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/".$id_artist."/albums");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['token'] ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_albums = curl_exec($ch);
        $artist = json_decode($result_artist);
        $albums = json_decode($result_albums)->items;
        if(isset($artist->images))
        {
            $artist =  new Artist($artist->id, $artist->name, $artist->images[0]->url, $artist->followers->total, $artist->external_urls->spotify);
            //if(!$artist->find($artist->id)){ $artist->create();}
        }
        else{
            $artist = new Artist($artist->id, $artist->name, "https://st.depositphotos.com/1269204/1219/i/450/depositphotos_12196477-stock-photo-smiling-men-isolated-on-the.jpg", $artist->followers->total, $artist->external_urls->spotify);
            //if(!$artist->find($artist->id)){ $artist->create();}
        }
        $listResp = [];
        foreach ($albums as $album){
            if(isset($album->images))
            {
                $listAlbums =  new Album($album->id, $album->name, $album->images[0]->url, $album->uri);

            }
            else{
                $listAlbums = new Album($album->id, $album->name, "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAoHCBUWFRgVFhUYGBgaGhgYGhwaGRwZGhkYGhoZHBkaGhocIS4lHB4rIRoYJjgmKy8xNTU1GiQ7QDs0Py40NTEBDAwMEA8QHhISHzQsJSs0NDQ2MTY0NjY0NDQ0NDQ0ND02NzQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ2NDQ0NDQ0NP/AABEIAOEA4QMBIgACEQEDEQH/xAAaAAACAwEBAAAAAAAAAAAAAAAAAwECBAUG/8QAPhAAAQMCBAMGBQMBBwMFAAAAAQACEQMhEjFBUQRhcQUigZGhsRMywdHwQlLh8QYUYoKSorIjM3IVQ1PC0v/EABoBAQADAQEBAAAAAAAAAAAAAAABAgMEBQb/xAAtEQACAgEDAwIFBAMBAAAAAAAAAQIRAxIhMQRBURMiYXGRobEUMkKBBeHwI//aAAwDAQACEQMRAD8ATQbYbWzB2HmFFPBBaIgEtI07p0nwVG0BABsCAY5wIMi4NhqrCmBmYJgDO8Tqcz62Xso+abq6NFQWsBpiJm4HMGx5pBZ3QabgQRYO7wi0REEdOaTxD3At+bMZa8iQRCgjACC3DrEk3iM7gTA1Stxrem6GO4XGIgsmJGmmRhLbRYx4glzg0gxfpJGRi11LOIfhMEA6EnInkfOEynUYAAW5a5gm8kDnvzVt7KNpJ0LcXONwQOcFULiJttFrEzcH9vVbW12xYjoPslsfM23z12A9FNmdK9xPBYy4m7pybYR0DtOnqn/G7+IgE7jQiwt/XJQ1zhnfbQAHPr02VmEEd4AyDacidRvlP5bPSuaOpTaWlNovW7wHeO9pBExOuceSyvpvmcZIv8wEmwzJGefqppNzEme7YzEenLy1lMNJ4/LC82U6EU9aXD4+Vmd4YXEHDiytBEXkNPI3unsa5oAkmJvaTe2QGXTZRVaCQ47cvC306I+K1oMlrQBPeOEDa5y8bZrPR3Z0es6SgyQbTF4sBMnpYDxySeOoFzflZ3jLpAuNByv+BPibjaxJJnaINwq02d7XEc7WtfJx65b6pKPY0xZNTt18hXZ/Cim2CMWdjk0G5AF/Hoioy8hjYOYIEDKwAAnW+d8lspsboZvB622vN1b4AbrPlsrRiqopLPGMm2nYvg2OccRNjpJN9Jkd3WwWpziCe6fQfVVYzr0Bi2Yy8VZ3I+c+N1ZKjmnPW74IP9UGpaw9LqHt6E6SJ9Ekgm2HLaD6yrJGUpMZ8d2YHlmoxvOkeR8M0stgS4wOf1VmgkWORjXTqmxCTe7sqZ6ehUuaGiM9T1Onl7lNbRvB5ze8DP62VnME+8/QHRLLKJnbewy1OfshtJvTne/ldXr18Mwc/reAsRqEziNkSZDcUbMDP3D/AE/yhYpQp0srrXgmgHtAuKjYEZMdkLD9Lv8Aao4niW/K4uaCJjDnyMj2TmUzhaBJtobaZTqlv4FznDE7ugGG3z+t1TTtybKacvcjlNrXOEWNokm2gF1qID4i5tMScv3CInpHQrQOy+8LnDeZAsMwBOqzupPYx0ODGuvhxd4xtGuSrTRtcZcCviQ7AWgXHMCfS9jcW2WlldgNqrBpAeDN+snXLdTw1ME4WtvIDnEWDY703MuNxEWyW+lLTGIFxJvYSdgLTb2RJlZuKff+jN8YkyMR0sx4b/qwwBzumMx3PwwANcQJcNLC86QtTHiSJJOVhkQb3yB6lJ46+FuQMyLbHPlmY1wo2/JEFFPj6nP/ALw90935TEG22Zvfl0smPa8AANxaXwnAQB8pJkgzkZiean+6AEYQCRbpcy4GLCANL2WpvDGIa4DWCJE2tfT8hVSfc3lLH8A4Oo5zjhoubAJOMtDcIBBLS0mwtn6p1RoIJLnAgizQRiIgEB0eeXiVRjHgAAgQTMDf/Lln5qzmvi8E5k3Dp8LFWSfczk4pXGjN8c4jLDGYdLRvaHYZjKeY2TBhBLjhBsdZy23ubqr3mRAIAiQRawIm0XnXmU6kQe8f5G1yUquSikpP2qvNDBfQEa631RRr4MUXBEOlodaQSAXAwctjzSfhDSTOYyk/kJrHjEDhAcLAxitzm2uynlUH7HdtMq97A6W8rnMWExbcmFdzZvN7xe1rnIX081R4IbMzJtPhJ9d/C6W0uIuQSDaLfg+yslsYTnbs1MdlaPvrG4VXvAEgHbKPdKa/CBcHle6o57pBlsDKBPjCtRTVsaKbJzJ9vRBqAGBcj8vdKxPObY6H8IV3vcM2wMoA+wUUWugdUg3HhKg1jq0jwP2UMBPyn1+31TBiP6p8h902CcmUPEQ3qY22P28iknEbN9x11WqpimNsrZ7+spb3nWB0T5BrfcyCkUxtKND1iys+oYyI5mxSQ4ncjrbzU7lXS4G4j+5vmoRi5D1QgF0jyJsBnttzv6K3x4BLnEgSYjlzJn+VnDjqZtAhRVa4iG2Fy45ANEkk8oBUUqssrcqRsZxjSXAE2j1GQvYLJxzsdRjQQLTJ/cSRaM+XVc6pxxpvhjmOs0utii5gXtkAbTnutFftKk9zbGnUDQS14hmKBdpzAMyJtfaFh60G6O6PS5UtSX9dze6kCQHOcdXHvCWtF2tjIElthdO4drdGsAbOGcwDneTnc23SKNQ4bgYoPntyVoBGfdMHlbYLfSjieSXDH0nwYaRAzuZB8es5zdWe0HSZSnVPwmVJdyTSZ+p4GNcJMDUnxJk6WzTGvGaRPn4R7oew52PX+FNEanyPNYdQolpGLT8sktMax4T4rB2rxoGFgIBOcnfeLDqs8klCNm+CDyzUbNFZ0CQ515JxEuDWkm4BPd1j8jdT7PDmB0iYxHvkbQIAyvPkvKHiXQZNyR0tHmItFtCs1XtB9++7nDiJvNwM7gFedOcpd6PoMOPHj4V/E9Xw1FuN1MvhwmDBIc6LM5GdU57HswhzvmmImBET43y+4Xi6HHPa4GcjPjuvS9l/2gsKb242EiW7RHeadHQD9VWE5wkqdrwWzYMWWDTVPydOMvfdLdSAzMA7XNtLeHmFBIBjGcgRncHK0wrMrDJon80AXqxdq0fNTilJxa3RlfS1AdHOSrM4iBeb2tt0TncQ0aHxjz2CWeME2bfcm3pmrbvsUWztMoOMGQgcsMf/AGlXo1jEAADeAOeUqC3FsfD6zKl3DnUDzJ+qUhbfHBLhexM8rx0kEhMY+DJeD4NHS41+yUKFthYWtz0zQeGZGsTOZCh09i0LTsuX5wf92XK8pZq3zPg766qH02N3PWSkPqAaAeM+6tRXUy7n+P5rv/Co51swPzYqrcTvlEc4v5JrOG1cfzwyU7IhJicY/d6D7IT4Zv6/yhRaLaX5K/KDaTHdmQJi19lR7g+gGNqND7/Fs8D/AMQ7BcZzFs90t2JzYLiByVqFWAJOLnuPzVZSx6qvg6cef0baSb8nnqtLC7LO06ei0cNUe1oAe61rEkCMrLqcVSBHdaBbSL88vRYmMsSAfp4LmlgcXaPQw9YprwdDhu0GFobUcWvh3eicURhDsukrS+nne05jI+Oq8vVZJldr+z3H02uwV5wGIcDdt0x53HaXAz9FHN7obP7M2Bl4IMWOn4ev4WgrdX4AHvUXiq21mjvjq3XqFz8fOCOULshKMlaZ4ubFPG6kqGtdpA0zvHVWHW2wNvBJJ5qIH4VejJSHFg0P54ryfa//AH3b2/4tXouIqhjS4kQ0Tl5a9Nl5v4mOpjjO8/e1lx9VJUonqf47HJyc1xVEVLCNdtlzavENmC/ly6Lq16c3K59Xh2nSVxs9mJFCTEXlbKFQB+GJ9Y6qOA4oMsAwyL4m4oG06Gyq/gGuqh7HENdDw0GS2cxfnOfJQX7HsaeQ72QGQa4eanBObn+Fvos/DOwtE7Rt+Zqzqs/kr1MP7UfNdXtmdDhQZuT1zVg3ZZTPPykeSpjJtLj4e85LQ5kmzfTotcQIF7eJslP49+J7g/uhzg1r2FwLQTGEkECwFrdU3hKGFpfLpMtbuBEucNZiGiMi/ku5SaxrW4w0EWyFnRNoH6RbLQrlzS3pdj1ekx6Yan3PPM7QBaHvaczdgdEggGGvzm2RUVXgy4EHU9xzHCLGxOlrglewc9hZNoI+Y27up65+K812hxLWtwNs5waXGwcGZta4/uNnHlCjFKTkkT1EMeltr6HKdLuXupaGtE4b7m3tdL7x1dHL7qGtM/LfnJ9Su08wY/iDFjA5AD/cUl1dxvPv7lMeLSbxoLn88FVhnJzQehcVGyJTtCsb9nef8IWjC7/5D/oQossLHCAj5jHWU1vD2sbeP4FWnU7o6DZW+J+E/wAKUZycrqzNxVCBYnoSb8llZULT/X0Wjiq5Nhac/wCpVadBwhwE+Ig+qq+djqg/Z7mFThi5uINj1/oue9kZrq/3pw+ZJq0cUxnsPrsubNh/kjr6fqGvbP8ApmXguOfTeHNJsV7ppHHjHRp/9RjQKkkAPMZjTF9+i8BUpELq8J2++iAyiMAgSf1Odq5x+mi5FKUJWuT0JQhlg4T4+6+R0q1MtcWuBa4WLTYg7EJVuSXxn9o3ViPjNDiLYoAMdQndnM4d5h9TBt/MQu6PUx03I8if+MmpVB2jD2lwxfTcBMxOe14jwXB4Vzw4yRDrAdDb2XpOOcxjsLHte2fmgmNrH6LJxPD04x4mExNvcXWeWMcvui+Do6ecum/8sie72MFcysFcgA3j85LoPErJXparjZ6SZzncU0EC/WLTz1XWpGXNcNR6rnspguyI6fS4tyXc4LhBiBAgRlz3RI0ujsiKmFrrFrNOsXvsB5LSzhWDIxYWvyBOvVYA9ocSCMgM9BkPMk+KsKjd48fP6r08KairPnetlqyvStjW5gk97Lr9FZrxHzTksY6+V0ylgDpeXEAF2HEBigSG56my0lsrZyQhqkoruQ+s5r2Og4MQ70GJBEztctXXFRtRrRjh2YNsjE4TF/5Uv4huAOwhwaRbBDWNa7vOw7TcNBgQCZXB47smkINB72PtckkHe7RAFxYDTWVwN6m2e9CCjFRb4VHpK/GstSnutGJxP7WyS2d3ER4rhPrFznPIMkySANdpMx4LJwHZNfG2Hh82dDibAXB23z0WntAtouDXuaSRihhxACSBLsrwTabQtcM4xu3RzdVhyZGlFWiQ0E3xCdScLfXLqsvB1m4y0Oufla2TJkCMRFzmQP6Lm8fx4f3GAxuYknTLIZW38ITVa5hZliF5vY2Oh0SWd/xJw9CtL18vt4PQPbUMwW92cQxAuBG4vFhklU+EE4nOM5zMeUXXF4V1Rrmvwtc0Els2HeziCIvoF2RXxmzAw3kBznNA0MkzPKY6K+LK5PdGXVdKsKuD+fk0fAbu7/Uf/wBIVPhN3H54oXTsedb8malTeQIcMhkf5VxScP1ADkB7pLWtgZZBMDG6H88yoRrJ7/6EcQS2YcDOeUqlPiSBBuMo/otFYWtAjKDHpF1ja4EyZOyq+TeFOO6NlJjYnCBti+i00SG2iOk/RKpPJEZeH8XVg3mfKforJHNJtumVq0Wu0Pkfqsr+Bhbw/wDxef8ARBrN/cFlLBGW5ti6rLDZbo5ruFlZXUSCu0arOvolVGNP4PouefTNcHbj65N000ch7Eitw2IQtnEsIKzBy5mj0Yu0KpOc2xvsmf3hpzkdQY81DimUmyqlqGUn0x3sQ80yj2mHPaynqQC8jutGsA5mPBZ6zARBy236qlJkZJuWtI97T7S4akRgDQRAxNbLhuZdeeiVxv8Aa0vcA+m19Oflfcm37swc15nhKUkJvEU+9bIT6GFam929yNSqqSXijrcZ2jweIFjKkEd5ofAadgCCXLfX7NoOYQGva+JwuJMSDDXNyafl+YjM7LzvCPFN7XluLCZg5THdPMg3vsF36XEd3Fniu14cQcV97A4pkG4MwSLnRSk9m2c8oY1vGK+grh+HNBrnF+ECYucN3OyHNb+P7OpmXsADox90kNIk3gd299F0W0W8TSa0uGNhBm8zhLbkEEggnYrNX4Q0aRDocGtLQ6CJtAaRoJcUWxVnmu0uPcyg1je6ahcXaQxkDAIyBdiP+ULgFjnG8leg7aDBUDCSfhsazL9XzOM9XFYxxFMZNK0jhlLciXUxhsk2/gcqnwrmumLZhRxFN7okHNdY8Y39nsoPHf4fZbLp1XJz/rcl/s+5zaNN1g2o21oNxH/iQQc811qXFOkBzaUQQcAeDOhAnDPhFysNQMcZLBPl7JvxH/tVoYtPP2ZnmzOa4W/mtjf/AHhvPyQsON+yFucHpfH7kta2B82Q1/hDmCPmPj/RVptEDujIbqXPaP0t8CVC4NN72Mzycp9vdS0EbehUuImQ3wCY2ry9AoSOmUmopUQB18AFLXkfv8x9k1j538LK+Ebeg+pVqOdzrZorjdz8XD7I+I7c/wCofZXNNm3qPurfDHTzKGeqPgzF7ifvdSGnc+QWoNG/opLW7+iEep4Rme0Fl7rlVG3XZIsQua+j3l5uanK0e107ajTENpaprbLQGAZrO9t1mdKdlXlM4ahJVTTK6fZlKT0UJbk2qNFCkGDEbR+DqUpvEDSSMspK0cUSXATYe+6yvc0G9+UuK9DFiilbPI6jqZSlpV7eCH8Ru3zspocc9k4QBOYNwRzac1AeNGein4kfpA8PsFp6cfBh68/j9TpcJxWM9wkO1puJAdpDHWt/hJ8Vuq8fgogkkPLjgZUyZhd85b+rLugx0MX8+K+fdBtGZEHcc+tlFbiXuMlxNgLmbDRZ+hHVfY2/VScWq38kPoBxLnPlxJJMTJNyVH90bv7eyVicdvL+FHj/ALVvRhc/I/4LdvdTlp7iPILOWnc+31VSRz8x9lJGm+XZpdUjVSagOp8is2PY+v8ACCTv6k/RCfSH4+vkEJH5qhQPTK0adhlkE3D081lYbDoFZQnsbvE292TUEG0eEq9OrFon85pBUhV7mrxpxp7mvHObT5fVWbUG3p/Kxz+SpB5K1mD6c1moNj6BT/eeXq37LIHDb1TKTcRAAHv/AFRvuQsC4qzXTrOInDA3tfkLXWisGYGljy4x3wQBBiYG4GXVdWl2c6m5lWqAbscMbgXGQXd5pu0W8FxH12vc4NiMxAibxJ9M91xvNKT24O39Hjxxtrf8Gd9RULpyVqjCqMYZWDbNoxVF20iUt7L5LZTMC6SHjZRRe2iWGdF1OEpNAkASVioBptC6paGsJ2BPkFeC3E2kjg8RVcXEzFzGXglF7v3ev8qnxCgu6L0kjydL5aLh+4B/zK3xIyaEoFGLkFIcL7DPjH+gCDVOs+n0Cpj6eSj4h3QafgMFTl9fdQTOkeSWXFRKE+m+yLwOXqgH8j+FSUSlk+nIuTz9FBj8CrKjEosuscvBZCriQlj05eBbH2F9Apx80luQ6BWVEzv9OJcvRiVEKSdCGYgjElqQhDghjTNt13+w+KZTJfAc4C0izf8AGeey4fDNEkkwB4m+cc807iOLEYWNwt2znmSbk/lly55X7S+NKLs29t9sF9ht6nNcjhq2F4Olweh/AfBAYXLfw/Z8CTAGpOQ5rm3vYmTtbmjATkbKPhlZ3cW1lmnENdB4Fa6ddrjAN4xRy6rVxpWzFWiHN7qTTp7hWpcU0ziLRe15Mc7JxrsAnPoPuquHdk6q2H0aI3XQdwLqrHMacLi0ls2DsNyJ2gETuQuRS4+TDWxzOf8AC7vDVsQY0mIsHH9JJztp9yilXBpGN/u4PEB6nEtHaNLBVe06OPqZHusq707VmTgkWxKJUIUkUiZRKEIKCUShCEhKEIQEIUqEAIUoQCmZDoFKqzIdArKEbAiUIUgFKhShDHUciN8vBTToElJBWmnxjm5YZ3i/2WGTE5O0VumdGnSZTGJ56DU8gFg4zjnPtGFu2/U6rO95cZJJO5VVaGJRIbsEykQMRJJluEDb+MyloV3BPkhNomE2jVixu38uElCNJqmQdJlMC4uF1OGfK89SrFuWWxyWn/1EgQ1sHeZjoIXO8LT2Lxk1syvarpqu8PYSsakqF0pUqKAhCFIJQoUoAQhCAEIQgBQpQgIQpQgEsyHQKSYzUMyHQKwVVwbFBUb+4WzuLI+Mz9zfMLh9md2thOTm+4DvuFParQKNOBAn6FYeu9LdcGmjejt/GZ+5vmFdrgciD0MrgdufJS6H2atFZxbxTItia0HnM5+Q8lLz02mvH3K6NvqdZtVpsHNJ6hS2o05OBjOCDC4jLcWRvPq2fddGo6KNR2+OOZJLR9FMcrad9r+xWUKr4mxtQETIjeRHmqtrMMw5trm4sNyuf/Z500o2cR5wfqsXBdziI0e3/kJ9wo9Z1F1yNG7Xg7zHg3BB6EFSx4cJBBHIyFmBw0ThGTXQBvePVY/7Ov8A+m4bO9wPsVf1PcovuiujZvwdZCELUzBCEIAQhCAEIQgBClQgJQhCAEIUIAUoUICUKEIBTMh0HsrBVZkOgUveAJJgDMlVXBscDje4aFT/AAtn/LH0Kb2x/wBlnUf8Sjj4dw7CDOHDPK0EcswqdpH/AKFIaw22uRXFLbUvNM2XYO3fkpdD7NWxhaK4D7uLRgIs0C9o0Od58li7ZcDTpQQbadGpnGOAr0nEjDDbzaxM3Tid/FCtq+YdpuwcQx2kNJ8yD6LqBncYw64Z8Glx9Qud2zTLjRMRiMHlJb/K6NV4NRrQ7CQHG0T+mBcHSVrFVKXi/wAmcuF/3Bz/AOzboxt2IPuD7BJ7UGE0ag0AB/ymfqVbst2GtUBPdOK5yMOBF8spTe0Gh/Dhzb4SDGo0II8Vmt8Wnur/ACWe078nQaZYwbub5A4vZq5vYPddVbsR6EhauFd3aLcRacOLSZiALg5yfJZOAGHiX5wcQnQkkHMWV2/dGX9FEtmjuIQhdZgCEIQAhCEAIQhACFKEAIQhAChShAQhShAQhShAJZkOgUcQ6GuJJAAJJGYjaVLMh0CTx1JzmOa2JIi9tb+kqsr0uvBsuTn0+LDoipVu7D+kaTOWSXS7SaSJfWE2nux7JHFcPUY5rg0/pdETDmgAgx08VZtN7XENDgxwDowY4n9JBtYyPALg1TujekdWkAZw13EjMS2R1BbISn1WC54h18ogzG0NWevwlQljmCHOYWukBukSRNjG2wU1uAcHNwsJa1uEQ4NJcbuM+JWrcvBSl5/A1/Esb/71Y2xWAsMpMtsg8QyWt+NVlwaR8sd7L9NlmfwT3OeO8MTRhMy12ECzz4Z7qX8G5+E/CLSAGOFgC3RzSTmPsq3Px+RUfP4NznMBg8Q8OmILgDPklVK7BJ+NVOEhroixM2+XkVnf2RUdikgmWhriblokSecYVpd2a7E50t7zy8iToHYRlubq3vfb8ke3yJPGNiRUrEYcWbR+rCBlmStHZ9Qvc4E1QWxLXOBBneGhYD2fVY0d3EQ8EgGZDRYeZcn9n/FNZzzTc0OmZtG0TE5KsXLWtSZMkqdHaQhC7jlBCEIAQpUIAQhCAFKEIAQhCAEIQgBCEICEIQgFMyHQKyqzIdB7KyhcGwIQpQApUKQpKkoQhCrBClCFSEIQgBCEIAQhSgIQhSgIQhSgBQpQgBCEIAQhCAEIQgIQpQgEsyHQKyEKFwbElQEIUlSyEIQMlAQhCrJQhCFUQpKEIAQhCAhShCAFCEICUBCEAIKEIAVkIQMqhCEIBCEISCEIQH//2Q==", $album->uri);

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
                    $listMusics[] = new Music($musique->id, $musique->name, "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT501yaAoiIhtJ7W264u-fUsLSpedBn6PI0QQ&usqp=CAU");
                }
            }
            $listResp[] = [$listAlbums, $listMusics];
        }

        $this->render('main/infoArtist', compact("artist","listResp" ));
    }
}
