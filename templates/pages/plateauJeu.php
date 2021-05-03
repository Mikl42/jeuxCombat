<?php
/*
 * template plateauJeu:  amont afficher_plateauJeu.php
 * 
 * parametre recu : obejet player (toutes les caracteristiques du player)
 * 
 */
?>

<!DOCTYPE html>

<html>

    <head>
        <?php include "templates/fragments/head.php"; ?>
        <title>plateau du jeu Combat - Ecran principal - créer By Aurore, Mick et Will</title>
    </head>

    <body class="plateauJeu">
        <main>
            <h1>jeu de combat</h1>

            <!-- zone player et adversaire --> 


            <div class="flex justify-around lg-95 first-zone" >

                <!-- zone player --> 
                <div class="lg-18 flex justify-between zoneplayer">
                    <?php include "templates/fragments/player.php"; ?>

                    <!-- zone adversaire --> 
                    <div class = " bg-primary lg-100 adversaire">
                        <h2>liste des adversaires</h2>
                        <?php include "templates/fragments/adversaire.php"; ?>
                    </div>
                </div>


                <!-- zone SALLE de JEU --> 

                <div class="lg-78 flex justify-around bg-primary zoneRoom">
                    <div class="lg-10 border">
                        <h2>entrée</h2>
                        <?php include "templates/fragments/entryRoom.php"; ?>
                    </div>
                    <div class="lg-75 border">
                        <h2>salle <?= $player->get("room") ?></h2>
                        <?php include "templates/fragments/roomPrincipal.php"; ?>
                    </div>
                    <div class="lg-10 border">
                        <h2>sortie</h2>
                        <?php include "templates/fragments/exitRoom.php"; ?>
                    </div>
                    <div class="flex justify-around lg-100 bouttonRoom">
                        <p><button onclick="previousRoom()" class="btn previousRoom">reculer</button></p>
                        <p><button onclick="nextRoom()" class="btn nextRoom">avancer</button></p>

                    </div>
                </div>
            </div>



            <!-- zone CONSOLE et MESSAGE -->      

            <div class="lg-100 bg-primary flex justify-between console">
                <div class="lg-70 border">
                    <h2>console : </h2>
                    <?php include "templates/fragments/console.php"; ?>
                </div>
                <div class="lg-28">
                    <h2>messages : </h2>                    
                    <?php include "templates/fragments/message.php"; ?>
                </div>

            </div>
        </main>

    </body>
    <?php include "templates/fragments/footer.php"; ?>

</html>