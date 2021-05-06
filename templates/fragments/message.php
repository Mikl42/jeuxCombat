<?php

/* 
 * fragment message
 */

?>

<p class="message">
    <?php
        if (isset($message)){
            ?>
        <div class="flex justify-around" >
            <p><?= $message ?></p>
        </div>
        <?php }
    ?>
    
</p> 