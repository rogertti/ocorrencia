<?php
    try {
        include_once('conexao.php');

        /* TENTA ATUALIZAR NO BANCO */

        $py = md5('idcliente');
        $mostra = 'F';
        $sql = $pdo->prepare("UPDATE cliente SET mostra = :mostra WHERE idcliente = :idcliente");
        $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
        $sql->bindParam(':idcliente', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();

            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            }
            else {
                header('location:cliente.php');
            }

        unset($pdo,$sql,$res,$py,$mostra);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>
