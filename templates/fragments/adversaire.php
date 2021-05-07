<?php
/*
 * fragment listes des advesaires : adversaire.php
 * parametre : tableau d'objet listePlayer
 * 
 */
?>


    <div>
        <?php
        if($player->get('room') == 0 OR $player->get('room') == 10){
            foreach ($listePlayer as $adversaire) {
                echo "<p>" . $adversaire->get('pseudo') . "</p>";
            }

        }else{
            foreach ($listePlayer as $adversaire) {
                echo "<p onclick='attaquer(".$adversaire->getId().")'>".$adversaire->get('pseudo')."</p>";
                }
        }
        ?>
    </div>

