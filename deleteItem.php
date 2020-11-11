<?php
    /* EXCLUIR ITEM */

    try {
        include_once('conexao.php');

        $pyconta = md5('idconta');
        $pyocorrencia = md5('idocorrencia');
        $pyserial = md5('idserial');
        $sql = $pdo->prepare("DELETE FROM conta WHERE idconta = :idconta");
        $sql->bindParam(':idconta', $_GET[''.$pyconta.''], PDO::PARAM_INT);
        $res = $sql->execute();

            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            }
            else {
                header('location:valorOcorrencia.php?'.$pyocorrencia.'='.$_GET[''.$pyocorrencia.''].'&'.$pyserial.'='.$_GET[''.$pyserial.''].'');
            }

        unset($pdo,$sql,$res,$pyconta,$pyocorrencia,$pyserial);
    }
    catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }
?>
