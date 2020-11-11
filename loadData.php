<?php
    $dia = substr($_POST['datado'],0,2);
    $mes = substr($_POST['datado'],3,2);
    $ano = substr($_POST['datado'],6);

        if(checkdate($mes, $dia, $ano)) {
            echo'true';
        }
        else {
            echo'false';
        }

    unset($ano,$mes,$dia);
?>
