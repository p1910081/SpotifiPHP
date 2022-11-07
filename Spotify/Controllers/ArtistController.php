<?php
namespace App\Controllers;


class ArtistController extends Controller
{
    public function search()
    {
        $data = $_POST['data'];
        $this->render('main/home', compact("data"));
    }
    public function info($data)
    {
        $this->render('main/infoArtist', compact("data"));
    }
}
