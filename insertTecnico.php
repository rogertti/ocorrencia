<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['nome'])) { die($msg); } else {
            $filtro = 1;

            $_POST['nome'] = str_replace("'","&#39;",$_POST['nome']);
            $_POST['nome'] = str_replace('"','&#34;',$_POST['nome']);
            $_POST['nome'] = str_replace('%','&#37;',$_POST['nome']);
        }

        if($filtro == 1) {
            try {
                include_once('conexao.php');

                /* CONTROLE DE DUPLICATAS */

                $sql = $pdo->prepare("SELECT idtecnico FROM tecnico WHERE nome = :nome");
                $sql->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        die('Escolha outro nome para o t&eacute;cnico.');
                    }

                unset($sql,$ret);

                /* TENTA INSERIR NO BANCO */

                $sql = $pdo->prepare("INSERT INTO tecnico (nome) VALUES (:nome)");
                $sql->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    }
                    else {
                        echo'true';
                    }

                unset($pdo,$sql,$res);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$filtro);
?>
