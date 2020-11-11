<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['cliente'])) { die($msg); } else {
            $filtro = 1;

            $_POST['cliente'] = str_replace("'","&#39;",$_POST['cliente']);
            $_POST['cliente'] = str_replace('"','&#34;',$_POST['cliente']);
            $_POST['cliente'] = str_replace('%','&#37;',$_POST['cliente']);
        }
        if(empty($_POST['serial'])) { die($msg); } else {
            $filtro++;
            $_POST['serial'] = strtoupper($_POST['serial']);
        }
        if(empty($_POST['datado'])) { die($msg); } else {
            $filtro++;

            $dia = substr($_POST['datado'],0,2);
            $mes = substr($_POST['datado'],3,2);
            $ano = substr($_POST['datado'],6);
            $_POST['datado'] = $ano."-".$mes."-".$dia;
            unset($dia,$mes,$ano);
        }
        if(empty($_POST['valor'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['receptor'])) { die($msg); } else { $filtro++; }


        if($filtro == 5) {
            try {
                include_once('conexao.php');

                /* CONTROLE DE DUPLICATAS */

                $sql = $pdo->prepare("SELECT idrecibo FROM recibo WHERE serial = :serial");
                $sql->bindParam(':serial', $_POST['serial'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        die('Esse serial j&aacute; possui um recibo.');
                    }

                unset($sql,$ret);

                /* TENTA INSERIR NO BANCO */

                $mostra = 'T';
                $sql = $pdo->prepare("INSERT INTO recibo (cliente,serial,datado,hora,valor,receptor,mostra) VALUES (:cliente,:serial,:datado,:hora,:valor,:receptor,:mostra)");
                $sql->bindParam(':cliente', $_POST['cliente'], PDO::PARAM_STR);
                $sql->bindParam(':serial', $_POST['serial'], PDO::PARAM_STR);
                $sql->bindParam(':datado', $_POST['datado'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['hora'], PDO::PARAM_STR);
                $sql->bindParam(':valor', $_POST['valor'], PDO::PARAM_STR);
                $sql->bindParam(':receptor', $_POST['receptor'], PDO::PARAM_STR);
                $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    }
                    else {
                        #echo'true';
                        $idrecibo = $pdo->lastInsertId();
                        $py = md5('idrecibo');
                        echo'<url>printRecibo.php?'.$py.'='.$idrecibo.'</url>';
                        unset($idrecibo,$py);
                    }

                unset($pdo,$sql,$res,$mostra);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$filtro);
?>
