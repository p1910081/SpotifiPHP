<?php
session_start();



//  Initiate curl
$ch = curl_init();

// Set the url
curl_setopt($ch, CURLOPT_URL,"https://api.spotify.com/v1/albums/");
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
// Print the return data
print_r(json_decode($result, true));



if (isset($_POST['nom'])) {
    echo "Bonjour ".htmlspecialchars($_POST['nom']);
    echo "Votre age ".(int)$_POST['age'];
    $_SESSION["nom"]=$_POST['nom'];

}