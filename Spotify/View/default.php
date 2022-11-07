<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Mes pages</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/test">Test</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <!--        --><?php //if(!empty($_SESSION['erreur'])): ?>
    <!--            <div class="alert alert-danger" role="alert">-->
    <!--                --><?php //echo $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
    <!--            </div>-->
    <!--        --><?php //endif; ?>
    <!--        --><?php //if(!empty($_SESSION['message'])): ?>
    <!--            <div class="alert alert-success" role="alert">-->
    <!--                --><?php //echo $_SESSION['message']; unset($_SESSION['message']); ?>
    <!--            </div>-->
    <!--        --><?php //endif; ?>
    <?= $contenu ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>

</html>