<?php
namespace App\Controllers;

abstract class Controller
{
    public function render(string $file, array $data = [], string $template = 'default'): void
    {
        // On extrait le contenu de $data
        extract($data);

        // On démarre le buffer de sortie
        ob_start();
        // A partir de ce point toute sortie est conservée en mémoire

        // On crée le chemin vers la vue
        require_once ROOT.'/View/'.$file.'.php';

        // Transfère le buffer dans $contenu
        $contenu = ob_get_clean();

        // Template de page
        require_once ROOT.'/View/'.$template.'.php';
    }
}