<?php
    ini_set('display_errors','off');

    try {
        include_once('conexao.php');

        $desativada = 'F';
        $dia = substr($_GET['search_keyword'],0,2);
        $mes = substr($_GET['search_keyword'],3,2);
        $ano = substr($_GET['search_keyword'],6);

            if(checkdate($mes, $dia, $ano)) {
                $client = 'Fundo Municipal de Saúde de Camboriú';
                $_GET['search_keyword'] = $ano.'-'.$mes.'-'.$dia;
                #$sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,fechada,desativada,entrega FROM ocorrencia WHERE datado = :datado AND desativada = :desativada ORDER BY datado DESC,hora DESC,cliente,serial");
                $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,fechada,desativada,entrega FROM ocorrencia WHERE cliente = :cliente AND datado = :datado ORDER BY datado DESC,hora DESC,cliente,serial");
                $sql->bindParam(':cliente', $client, PDO::PARAM_STR);
                $sql->bindParam(':datado', $_GET['search_keyword'], PDO::PARAM_STR);
                #$sql->bindParam(':desativada', $desativada, PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();
            }
            else {
                $client = 'Fundo Municipal de Saúde de Camboriú';
                $_GET['search_keyword'] = '%'.$_GET['search_keyword'].'%';
                #$sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,fechada,desativada,entrega FROM ocorrencia WHERE ((serial LIKE :search) OR (cliente LIKE :search) OR (solicitacao LIKE :search) OR (diagnostico LIKE :search) OR (procedimento LIKE :search) OR (observacao LIKE :search)) AND desativada = :desativada ORDER BY datado DESC,hora DESC,cliente,serial");
                $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,fechada,desativada,entrega FROM ocorrencia WHERE cliente = :cliente AND ((serial LIKE :search) OR (solicitacao LIKE :search) OR (diagnostico LIKE :search) OR (procedimento LIKE :search) OR (observacao LIKE :search)) ORDER BY datado DESC,hora DESC,cliente,serial");
                $sql->bindParam(':cliente', $client, PDO::PARAM_STR);
                $sql->bindParam(':search', $_GET['search_keyword'], PDO::PARAM_STR);
                #$sql->bindParam(':desativada', $desativada, PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();
            }

        unset($dia,$mes,$ano);

            if($ret > 0) {
                $py = md5('idocorrencia');

                    // CONTANDO AS OCORRENCIAS

                    if($ret == 1) {
                        echo'
                        <a title="N&uacute;mero de ocorr&ecirc;ncias encontradas">
                            <h2>'.$ret.' ocorr&ecirc;ncia encontrada</h2>
                        </a>
                        <hr>';
                    }
                    else {
                        echo'
                        <a title="N&uacute;mero de ocorr&ecirc;ncias encontradas">
                            <h2>'.$ret.' ocorr&ecirc;ncias encontradas</h2>
                        </a>
                        <hr>';
                    }

                    while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                        //TRATANDO A DATA
                        $ano = substr($lin->datado,0,4);
                        $mes = substr($lin->datado,5,2);
                        $dia = substr($lin->datado,8);
                        $lin->datado = $dia."/".$mes."/".$ano;

                            if($lin->desativada == 'T'){
                                $desativado = '<h4 class="label label-danger">OCORR&Ecirc;NCIA DESATIVADA</h4>';
                                $ativar = '<span><a class="tt text text-danger" title="Ativar a ocorr&ecirc;ncia" href="activateOcorrencia.php?'.$py.'='.$lin->idocorrencia.'"><i class="fa fa-arrow-circle-up fa-lg"></i></a></span>';
                                
                            }else{
                                $desativado = '';
                                $ativar = '';
                            }
                        
                        echo'
                        <span>
                            '.$desativado.'
                            <h2>'.$lin->serial.'</h2>
                            <h4><strong>'.$lin->cliente.':</strong> '.$lin->solicitacao.'</h4>
                            <h4><strong>Diagn&oacute;stico:</strong> '.$lin->diagnostico.'</h4>
                            <h4><strong>Procedimento:</strong> '.$lin->procedimento.'</h4>
                            <h4><strong>Observa&ccedil;&atilde;o:</strong> '.$lin->observacao.'</h4>
                            <h4><strong>Item:</strong> '.$lin->descricao.'</h4>
                            <p>'.$lin->tecnico.' - '.$lin->datado.' - '.$lin->hora.' h</p>
                            <p>
                                <!--<span><a class="delete-oc" id="'.$py.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-lg fa-trash-o"></i></a></span>
                                <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrenciaSaude.php?'.$py.'='.$lin->idocorrencia.'"><i class="fa fa-lg fa-pencil"></i></a></span>-->
                                '.$ativar.'
                            </p>
                        </span>
                        <hr>';

                        unset($dia,$mes,$ano,$desativado,$ativar);
                    }

                unset($lin,$py);
            }
            else {
                echo'
                <div style="margin-top: 30px;">
                    <p class="lead"><strong>Nenhuma ocorr&ecirc;ncia encontrada.</strong></p>
                </div>';
            }

        unset($pdo,$sql,$ret,$desativada);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>