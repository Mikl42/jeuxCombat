<?php

/*
 * template plateauJeu:  amont afficher_plateauJeu.php
 * 
 * parametre recu :
 *          - objet player (toutes les caracteristiques du player)
 *          - listePlayer : Une liste de tous les joueurs qui sont dans la meme piece
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
                <div class=" bg-primary player lg-100">
                    <h2>personnage</h2>
                    <div class="flex justify-around" >
                        <p>pv</p>
                        <span><?= $player->get("hp") ?></span>
                    </div>
                    <div class="flex justify-around" >
                        <p>Force</p>
                        <span class="strength"><?=  $player->get("strength") ?></span>
                    </div>
                    <div class="flex justify-around" >
                        <p>agilité</p>
                        <span class="agility"><?= $player->get("agility")?></span>
                    </div>
                    <div class="flex justify-around" >
                        <p>resistance</p>
                        <span class="resistance"><?= $player->get("resistance") ?></span>
                    </div>
                    <p><button onclick="augmenterStrength(<?= $player->getId()?>)" class="btn">augmenter Force</button></p>
                    <p><button onclick="augmenterRes(<?= $player->getId()?>)"  class="btn">augmenter resistance</button></p>

                </div>
                
                
                <!-- zone adversaire --> 
                
                
                <div class=" bg-primary lg-100 adversaire">
                    <h2>liste des adversaires</h2>
                    <!--  $otherPlayer //foreach chaque adversaire present ds la salle afficher les pseudo  -->
                    <?php
                        foreach ($listePlayer as $adverssaire){
                            echo "<p> $adverssaire->get('pseudo') </p>";
                        }


                    ?>

                </div>
            </div>
            
            
            <!-- zone SALLE de JEU --> 
            
            <div class="lg-78 flex justify-around bg-primary zoneRoom">
                <div class="lg-10 border">
                    <h2>entrée</h2>
                </div>
                <div class="lg-75 border">
                    <h2>salle <!-- $room --> recuperation du numero de la salle courante ?></h2>
                </div>
                <div class="lg-10 border">
                    <h2>sortie</h2>
                </div>
                <div class="flex justify-around lg-100 bouttonRoom">
                    <p><button class="btn">reculer</button></p>
                    <p><button class="btn">avancer</button></p>
                    
                </div>
            </div>
        </div>
        
        
        
        <!-- zone CONSOLE et MESSAGE -->      
        
        <div class="lg-100 bg-primary flex justify-between console">
            <div class="lg-70 border">
                <h2>console : </h2>
                <p class="console"></p>
            </div>
            <div class="lg-28">
                <h2>messages : </h2>
                <p class="message"></p> 
            </div>
            
        </div>
    </main>

</body>
<?php include "templates/fragments/footer.php"; ?>

</html>