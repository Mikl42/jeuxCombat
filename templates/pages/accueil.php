<?php
/*page accueil : amont index.php
form se connecter -> POST details de connexion
ou création perso -> direction vers controler afficher_form_crea_perso.php

param : neant
 *
 *
 */
?>


<!DOCTYPE html>

<html>
<head>
    <?php include "templates/fragments/head.php"; ?>
    <title>page d'accueil - Jeu combat - By Aurore, Mick, Will</title>

</head>
<body>
<main>
    <div>
        <h1>JEU COMBAT</h1>
        <div class="container flex justify-between">
            <form method="POST" action="afficher_plateauJeu.php" class="lg-48">
                <label> votre pseudo :
                    <input type="text" name="pseudo"/>
                </label> <br>
                <label> votre mot de passe :
                    <input type="text" name="pwd"/>
                </label> <br>
                <input class="btn" type="submit" value="Se Connecter"/>
            </form>
            <div class="lg-48">
                <h2>Créer votre nouveau perso :</h2>
                <a class="btn" href="afficher_form_crea_perso.php">Créer</a>
            </div>
        </div>
    </div>

</main>

<?php include "templates/fragments/footer.php"; ?>



</body>
</html>