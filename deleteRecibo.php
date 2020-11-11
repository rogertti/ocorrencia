<?php
    try {
        include_once('conexao.php');

        /* TENTA ATUALIZAR NO BANCO */

        $py = md5('idrecibo');
        $mostra = 'F';
        $sql = $pdo->prepare("UPDATE recibo SET mostra = :mostra WHERE idrecibo = :idrecibo");
        $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
        $sql->bindParam(':idrecibo', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();

            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            }
            else {
                header('location:recibo.php');
            }

        unset($pdo,$sql,$res,$py,$mostra);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>
