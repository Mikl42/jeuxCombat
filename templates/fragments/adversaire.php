<?php
/*
 * fragment listes des advesaires : adversaire.php
 * parametre : tableau d'objet listePlayer
 * 
 */
?>


    <div>
        <?php
        foreach ($listePlayer as $adversaire) {
            echo "<p><a href='" . $adversaire->getId() . "'> " . $adversaire->get('pseudo') . " </a></p>";
        }
        ?>
    </div>

