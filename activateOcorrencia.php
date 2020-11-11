<?php
    include_once('conexao.php');

    try {
        $py = md5('idocorrencia');
        $desativada = 'F';
        $sql = $pdo->prepare("UPDATE ocorrencia SET desativada = :desativada WHERE idocorrencia = :idocorrencia");
        $sql->bindParam(':desativada', $desativada, PDO::PARAM_STR);
        $sql->bindParam(':idocorrencia', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();
        
            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            } else {
                header('location:inicio.php');
            }

        unset($pdo,$sql,$res,$status,$py);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }   
?>