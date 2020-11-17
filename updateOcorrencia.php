<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['idocorrencia'])) { die("reload"); }
        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['cliente'])) { die($msg); } else {
            $filtro = 1;

            $_POST['cliente'] = str_replace("'","&#39;",$_POST['cliente']);
            $_POST['cliente'] = str_replace('"','&#34;',$_POST['cliente']);
            $_POST['cliente'] = str_replace('%','&#37;',$_POST['cliente']);
        }
        if(empty($_POST['datado'])) { die($msg); } else {
            $filtro++;

            $dia = substr($_POST['datado'],0,2);
            $mes = substr($_POST['datado'],3,2);
            $ano = substr($_POST['datado'],6);
            $_POST['datado'] = $ano."-".$mes."-".$dia;
            unset($dia,$mes,$ano);
        }
        if(empty($_POST['hora'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['tecnico'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['solicitacao'])) { die($msg); } else {
            $filtro++;

            $_POST['solicitacao'] = str_replace("'","&#39;",$_POST['solicitacao']);
            $_POST['solicitacao'] = str_replace('"','&#34;',$_POST['solicitacao']);
            $_POST['solicitacao'] = str_replace('%','&#37;',$_POST['solicitacao']);
        }
        if(!empty($_POST['diagnostico'])) {
            $_POST['diagnostico'] = str_replace("'","&#39;",$_POST['diagnostico']);
            $_POST['diagnostico'] = str_replace('"','&#34;',$_POST['diagnostico']);
            $_POST['diagnostico'] = str_replace('%','&#37;',$_POST['diagnostico']);
        }
        if(!empty($_POST['procedimento'])) {
            $_POST['procedimento'] = str_replace("'","&#39;",$_POST['procedimento']);
            $_POST['procedimento'] = str_replace('"','&#34;',$_POST['procedimento']);
            $_POST['procedimento'] = str_replace('%','&#37;',$_POST['procedimento']);
        }
        if(!empty($_POST['observacao'])) {
            $_POST['observacao'] = str_replace("'","&#39;",$_POST['observacao']);
            $_POST['observacao'] = str_replace('"','&#34;',$_POST['observacao']);
            $_POST['observacao'] = str_replace('%','&#37;',$_POST['observacao']);
        }
        if(empty($_POST['viacliente'])) {
            $_POST['viacliente'] = 'F';
        }

        if($filtro == 5) {
            try {
                include_once('conexao.php');

                /* CONTROLE DE DUPLICATAS */

                $desativada = 'F';
                $sql = $pdo->prepare("SELECT idocorrencia FROM ocorrencia WHERE serial = :serial AND desativada = :desativada AND idocorrencia <> :idocorrencia");
                $sql->bindParam(':serial', $_POST['serial'], PDO::PARAM_STR);
                $sql->bindParam(':desativada', $desativada, PDO::PARAM_STR);
                $sql->bindParam(':idocorrencia', $_POST['idocorrencia'], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        die('Esse serial est&aacute; em uso, recarregue a p&aacute;gina.');
                    }
                    else {
                        // VERIFICA SE O TECNICO NAO POSSUI ATENDIMENTO NO HORARIO SOLICITADO

                        $fechada = 'F';
                        $desativada = 'F';
                        $sql2 = $pdo->prepare("SELECT idocorrencia FROM ocorrencia WHERE datado = :datado AND hora = :hora AND tecnico = :tecnico AND fechada = :fechada AND desativada = :desativada AND idocorrencia <> :idocorrencia");
                        $sql2->bindParam(':datado', $_POST['datado'], PDO::PARAM_STR);
                        $sql2->bindParam(':hora', $_POST['hora'], PDO::PARAM_STR);
                        $sql2->bindParam(':tecnico', $_POST['tecnico'], PDO::PARAM_STR);
                        $sql2->bindParam(':fechada', $fechada, PDO::PARAM_STR);
                        $sql2->bindParam(':desativada', $desativada, PDO::PARAM_STR);
                        $sql2->bindParam(':idocorrencia', $_POST['idocorrencia'], PDO::PARAM_INT);
                        $sql2->execute();
                        $ret2 = $sql2->rowCount();

                            if($ret2 > 0) {
                                die('O t&eacute;cnico '.$_POST['tecnico'].' j&aacute; tem um atendimento nesse hor&aacute;rio.');
                            }

                        unset($sql2,$ret2,$fechada,$desativada);
                    }

                unset($sql,$ret);

                /* TENTA INSERIR NO BANCO */

                $sql = $pdo->prepare("UPDATE ocorrencia SET cliente = :cliente, datado = :datado, hora = :hora, solicitacao = :solicitacao, diagnostico = :diagnostico, procedimento = :procedimento, observacao = :observacao, tecnico = :tecnico, retorno = :retorno, pagamento = :pagamento, fechada = :fechada, entrega = :entrega WHERE idocorrencia = :idocorrencia");
                $sql->bindParam(':cliente', $_POST['cliente'], PDO::PARAM_STR);
                $sql->bindParam(':datado', $_POST['datado'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['hora'], PDO::PARAM_STR);
                $sql->bindParam(':solicitacao', $_POST['solicitacao'], PDO::PARAM_STR);
                $sql->bindParam(':diagnostico', $_POST['diagnostico'], PDO::PARAM_STR);
                $sql->bindParam(':procedimento', $_POST['procedimento'], PDO::PARAM_STR);
                $sql->bindParam(':observacao', $_POST['observacao'], PDO::PARAM_STR);
                $sql->bindParam(':tecnico', $_POST['tecnico'], PDO::PARAM_STR);
                $sql->bindParam(':retorno', $_POST['retorno'], PDO::PARAM_STR);
                $sql->bindParam(':pagamento', $_POST['pagamento'], PDO::PARAM_STR);
                $sql->bindParam(':fechada', $_POST['fechada'], PDO::PARAM_STR);
                $sql->bindParam(':entrega', $_POST['entrega'], PDO::PARAM_STR);
                $sql->bindParam(':idocorrencia', $_POST['idocorrencia'], PDO::PARAM_INT);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    }
                    else {
                        if (($_POST['fechada'] == "T") or ($_POST['entrega'] == "T")) {
                            $pyocorrencia = md5('idocorrencia');
                            $pyserial = md5('idserial');
                            echo'<url>valorOcorrencia.php?'.$pyocorrencia.'='.$_POST['idocorrencia'].'&'.$pyserial.'='.$_POST['serial'].'</url>';
                            unset($pyocorrencia,$pyserial);
                        } elseif($_POST['viacliente'] == 'T') {
                            $pyocorrencia = md5('idocorrencia');
                            echo'<url>printViaOcorrencia.php?'.$pyocorrencia.'='.$_POST['idocorrencia'].'</url>';
                            unset($pyocorrencia);
                        } else {
                            echo'true';
                        }
                    }

                unset($pdo,$sql,$res,$desativada);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$filtro);
?>
