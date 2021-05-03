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
    <p><button onclick="augmenterStrength(<?= $player->getId() ?>)" class="btn">augmenter Force</button></p>
    <p><button onclick="augmenterRes(<?= $player->getId() ?>)"  class="btn">augmenter resistance</button></p>

</div>