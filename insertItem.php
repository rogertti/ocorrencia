<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) { die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['idocorrencia'])) { die("reload"); }
        if(empty($_POST['descricao'])) { die($msg); } else {
            $filtro = 1;

            $_POST['descricao'] = str_replace("'","&#39;",$_POST['descricao']);
            $_POST['descricao'] = str_replace('"','&#34;',$_POST['descricao']);
            $_POST['descricao'] = str_replace('%','&#37;',$_POST['descricao']);
        }
        if(empty($_POST['valor'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['quantidade'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['desconto'])) { $_POST['desconto'] = '0.00'; }
        if(empty($_POST['subtotal'])) { die($msg); } else { $filtro++; }

        if($filtro == 4) {
            try {
                include_once('conexao.php');

                /* TENTA INSERIR NO BANCO */

                $sql = $pdo->prepare("INSERT INTO conta (idocorrencia,descricao,quantidade,vunitario,vtotal,desconto) VALUES (:idocorrencia,:descricao,:quantidade,:valor,:subtotal,:desconto)");
                $sql->bindParam(':idocorrencia', $_POST['idocorrencia'], PDO::PARAM_INT);
                $sql->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR);
                $sql->bindParam(':valor', $_POST['valor'], PDO::PARAM_STR);
                $sql->bindParam(':quantidade', $_POST['quantidade'], PDO::PARAM_STR);
                $sql->bindParam(':desconto', $_POST['desconto'], PDO::PARAM_STR);
                $sql->bindParam(':subtotal', $_POST['subtotal'], PDO::PARAM_STR);
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
