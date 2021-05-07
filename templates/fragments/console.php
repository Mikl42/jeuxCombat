<?php

/* 
 * fragment console
 */

?>

<div class="console">

    <?php
        foreach ($listeHistoricPlayer as $historic){
            echo "<p> Date : ".$historic->get('timesaction')." Salle : ".$historic->get('room').", Agilité : ".$historic->get('agility')." hp : ".$historic->get('hp')."</p>";
        }
    ?>
    
</div>