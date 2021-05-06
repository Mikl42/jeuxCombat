<?php
/*
 * fragment du plateauJeu
 * zone player

 */
?>
<div class=" bg-primary player lg-100">
    <h2>personnage</h2>
    <div class="flex justify-around" >
        <p>pv</p>
        <span class="hp"><?= $player->get("hp") ?></span>
    </div>
    <div class="flex justify-around" >
        <p>Force</p>
        <span class="strength"><?= $player->get("strength") ?></span>
    </div>
    <div class="flex justify-around" >
        <p>agilité</p>
        <span class="agility"><?= $player->get("agility") ?></span>
    </div>
    <div class="flex justify-around" >
        <p>resistance</p>
        <span class="resistance"><?= $player->get("resistance") ?></span>
    </div>
    <div class="flex justify-around" >
        <?php if (isset($message) AND (!empty($message))) {
            echo $message;
        }
        ?> 
    </div>
    <?php if ($agility >= 3 and $strength >= 1) { ?>
        <p><button onclick="augmenterStrength()" class="btn">augmenter Force</button></p>
    <?php } else {
        ?>
        <p><button onclick="augmenterStrength()" class="btn disable" disabled="disabled">augmenter Force</button></p>
    <?php
    }
    if ($agility >= 3 and $resistance >= 1) {
        ?>
        <p><button onclick="augmenterRes()"  class="btn">augmenter resistance</button></p>
<?php } else { ?>
        <p><button onclick="augmenterRes()"  class="btn disable" disabled="disabled">augmenter resistance</button></p>
<?php } ?>

</div>


