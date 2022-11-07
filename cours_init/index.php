<?php





require_once('Fruit.php');
$apple = new Fruit();
$apple->set_name('Apple');
echo $apple->get_name();
?>

<form action="action.php" method="post">
 <p>Votre nom : <input type="text" name="nom" /></p>
 <p>Votre âge : <input type="text" name="age" /></p>
 <p><input type="submit" value="OK"></p>
</form>



