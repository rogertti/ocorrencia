<?php
    try {
        include_once('conexao.php');

        /* TENTA ATUALIZAR NO BANCO */

        $py = md5('idnota');
        $mostra = 'F';
        $sql = $pdo->prepare("UPDATE nota SET mostra = :mostra WHERE idnota = :idnota");
        $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
        $sql->bindParam(':idnota', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();

            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            }
            else {
                header('location:inicio.php');
            }

        unset($pdo,$sql,$res,$py,$mostra);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>
