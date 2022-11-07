<?php
namespace App\Controllers;


class MainController extends Controller
{

    public function form()
    {
        $this->render('main/form', []);
    }
    public function search()
    {
        $data = $_POST['data'];
        $this->render('main/home', compact("data"));
    }
    public function info()
    {
        $data = $_POST['data'];
        $this->render('main/infoArtist', compact("data"));
    }
}