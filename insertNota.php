<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['datado'])) { die($msg); } else {
            $filtro = 1;

            $dia = substr($_POST['datado'],0,2);
            $mes = substr($_POST['datado'],3,2);
            $ano = substr($_POST['datado'],6);
            $_POST['datado'] = $ano."-".$mes."-".$dia;
            unset($dia,$mes,$ano);
        }
        if(empty($_POST['tecnico'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['texto'])) { die($msg); } else {
            $filtro++;

            $_POST['texto'] = str_replace("'","&#39;",$_POST['texto']);
            $_POST['texto'] = str_replace('"','&#34;',$_POST['texto']);
            $_POST['texto'] = str_replace('%','&#37;',$_POST['texto']);
        }

        if($filtro == 3) {
            try {
                include_once('conexao.php');

                /* CONTROLE DE DUPLICATAS */

                /*$sql = $pdo->prepare("SELECT idnota FROM nota WHERE usuario = :usuario");
                $sql->bindParam(':usuario', $_POST['usuario'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        die('Escolha outro nome de usu&aacute;rio.');
                    }

                unset($sql,$ret);*/

                /* TENTA INSERIR NO BANCO */

                $mostra = 'T';
                $sql = $pdo->prepare("INSERT INTO nota (datado,tecnico,texto,mostra) VALUES (:datado,:tecnico,:texto,:mostra)");
                $sql->bindParam(':datado', $_POST['datado'], PDO::PARAM_STR);
                $sql->bindParam(':tecnico', $_POST['tecnico'], PDO::PARAM_STR);
                $sql->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR);
                $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    }
                    else {
                        echo'true';
                    }

                unset($pdo,$sql,$res,$mostra);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$filtro);
?>
