<?php
if (!empty($errorField)):
    foreach($errorField as $err):
        echo $err . "<br>";
    endforeach;
endif;
