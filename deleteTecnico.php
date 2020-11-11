<?php
    /* EXCLUIR TECNICO */

    try {
        include_once('conexao.php');

        $py = md5('idtecnico');
        $sql = $pdo->prepare("DELETE FROM tecnico WHERE idtecnico = :idtecnico");
        $sql->bindParam(':idtecnico', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();

            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            }
            else {
                header('location:tecnico.php');
            }

        unset($pdo,$sql,$res,$py);
    }
    catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }
?>
