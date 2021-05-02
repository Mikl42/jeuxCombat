<?php
/* page créer perso : amont afficher_form_crea_perso.php
  envoie les info du formulaire en post vers enregistrer_perso.php

  param : neant
 *
 *
 */
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <?php include "templates/fragments/head.php"; ?>
    <title>Créer un nouveau personnage - jeux combat - Aurore, Mick, Will</title>
</head>
<body class="form">
<main>
    <div class="container">

        <h1>Créer votre nouveau perso</h1>
        <div class="flex">

            <form action="enregistrer_perso.php" method="POST" class="lg-48">
                <label> votre pseudo :
                    <input type="text" name="pseudo"/>
                </label> <br>
                <label> votre mot de passe :
                    <input type="password" name="pwd"/>
                </label> <br>
                <p>Stats : <span class="pointStatsStart" >15</span></p><br>
                <label>force
                    <input onchange="strengthStart" type="number" name="strength" step="1" max="15" min="0">
                </label><br>
                <label>agilité
                    <input onchange="agilityStart" type="number" name="agility" step="1" max="15" min="0">
                </label><br>
                <label>résistance
                    <input onchange="resistanceStart" type="number" name="resistance" step="1" max="15" min="0">
                </label><br>

                <input class="btn" type="submit" value="enregistrer"/>
            </form>
            <div  class="lg-20">
                <img src="img/warrior.png" alt=""/>
            </div>
            <div  class="lg-20">
                <img src="img/woman.png" alt=""/>
            </div>
        </div>
    </div>
</main>

<?php include "templates/fragments/footer.php"; ?>
</body>
</html>