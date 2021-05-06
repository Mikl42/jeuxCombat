<?php
/*
 * fragment template body
 */
?>

<main class="putaindeMain">
    <h1>jeu de combat</h1>

    <!--zone player et adversaire-->


    <div class = "flex justify-around lg-95 first-zone" >

        <!--zone player-->
        <div class = "lg-18 flex justify-between zoneplayer">
            <?php include "templates/fragments/player.php";
            ?>                    
        </div>


        <!-- zone SALLE de JEU --> 

        <div class="lg-78 flex justify-around bg-primary zoneRoom">

            <div class="lg-75 border">

                <?php include "templates/fragments/roomPrincipal.php"; ?>
            </div>
            <!-- zone adversaire --> 
            <div class = " bg-primary lg-20 adversaire">
                <h2>liste des adversaires</h2>
                <?php include "templates/fragments/adversaire.php"; ?>
            </div>                    
            <div class="flex justify-start lg-50 bouttonRoom">

                <?php if ($room > 0) { ?>
                    <p><button onclick="previousRoom()" class="btn previousRoom">reculer</button></p>
                <?php } else {
                    ?>
                    <p><button class="btn previousRoom disable" disabled="disabled">reculer</button></p>
                <?php }
                if ($agility > $room) {
                    ?>
                    <p><button onclick="nextRoom()" class="btn nextRoom">avancer</button></p>
                <?php } else { ?>
                    <p><button class="btn nextRoom disable" disabled="disabled">avancer</button></p>
                <?php } ?>

            </div>
        </div>
    </div>



    <!-- zone CONSOLE et MESSAGE -->      

    <div class="lg-100 bg-primary console">   
        <h2>console : </h2>
        <div class="border">                    
            <?php include "templates/fragments/console.php"; ?>
        </div>               

    </div>
</main>

