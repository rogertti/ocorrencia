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
        if(!empty($_POST['nascimento'])) {
            $dia = substr($_POST['nascimento'],0,2);
            $mes = substr($_POST['nascimento'],3,2);
            $ano = substr($_POST['nascimento'],6);
            $_POST['nascimento'] = $ano."-".$mes."-".$dia;
            unset($dia,$mes,$ano);
        }
        if(empty($_POST['documento'])) { die($msg); } else { $filtro++; }

        if($filtro == 2) {
            try {
                include_once('conexao.php');

                /* CONTROLE DE DUPLICATAS */

                $sql = $pdo->prepare("SELECT idcliente FROM cliente WHERE cpf_cnpj = :documento");
                $sql->bindParam(':documento', $_POST['documento'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        die('Esse cliente j&aacute; est&aacute; cadastrado.');
                    }

                unset($sql,$ret);

                /* TENTA INSERIR NO BANCO */

                $mostra = 'T';
                $sql = $pdo->prepare("INSERT INTO cliente (nome,cpf_cnpj,rg_ie,cep,endereco,bairro,cidade,estado,telefone,celular,email,nascimento,observacao,mostra) VALUES (:nome,:cpf_cnpj,:rg_ie,:cep,:endereco,:bairro,:cidade,:estado,:telefone,:celular,:email,:nascimento,:observacao,:mostra)");
                $sql->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
                $sql->bindParam(':cpf_cnpj', $_POST['documento'], PDO::PARAM_STR);
                $sql->bindParam(':rg_ie', $_POST['documento2'], PDO::PARAM_STR);
                $sql->bindParam(':cep', $_POST['cep'], PDO::PARAM_STR);
                $sql->bindParam(':endereco', $_POST['endereco'], PDO::PARAM_STR);
                $sql->bindParam(':bairro', $_POST['bairro'], PDO::PARAM_STR);
                $sql->bindParam(':cidade', $_POST['cidade'], PDO::PARAM_STR);
                $sql->bindParam(':estado', $_POST['estado'], PDO::PARAM_STR);
                $sql->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR);
                $sql->bindParam(':celular', $_POST['celular'], PDO::PARAM_STR);
                $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $sql->bindParam(':nascimento', $_POST['nascimento'], PDO::PARAM_STR);
                $sql->bindParam(':observacao', $_POST['observacao'], PDO::PARAM_STR);
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
