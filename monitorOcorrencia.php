<?php
    try {
        include_once('conexao.php');
        
        //BUSCANDO AS NOTAS

        $mostra = 'T';
        $sql = $pdo->prepare("SELECT idnota,datado,tecnico,texto FROM nota WHERE mostra = :mostra ORDER BY idnota DESC");
        $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $pyidnota = md5('idnota');

                echo'
                <div class="row">
                    <div class="col-md-2 color-palette-set">
                        <span class="label label-warning">NOTAS</span>
                    </div>
                </div>
                <div class="spacing-row-1"></div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover">';

                    while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                        //INVERTENDO A DATA

                        $ano = substr($lin->datado,0,4);
                        $mes = substr($lin->datado,5,2);
                        $dia = substr($lin->datado,8);
                        $lin->datado = $dia."/".$mes."/".$ano;

                            if($lin->tecnico == 'OC') {
                                echo'
                                <tr>
                                    <td class="td-action">
                                        
                                    </td>
                                    <td>'.$lin->datado.' &raquo; <strong>'.$lin->tecnico.'</strong> &raquo; '.$lin->texto.'</td>
                                </tr>';
                            } else {
                                echo'
                                <tr>
                                    <td class="td-action">
                                        <span><a class="delete-nota" id="'.$pyidnota.'-'.$lin->idnota.'" title="Excluir a nota" href="#"><i class="fa fa-trash-o"></i></a></span>
                                        <span><a data-toggle="modal" data-target="#edita-nota" title="Editar a nota" href="editaNota.php?'.$pyidnota.'='.$lin->idnota.'"><i class="fa fa-pencil"></i></a></span>
                                    </td>
                                    <td>'.$lin->datado.' &raquo; <strong>'.$lin->tecnico.'</strong> &raquo; '.$lin->texto.'</td>
                                </tr>';
                            }

                        unset($dia,$mes,$ano);
                    }

                echo'
                        </table>
                    </div>
                </div>
                <hr>';

                unset($lin);
            }

        unset($sql,$ret);

        //BUSCANDO AS OCORRENCIAS

        $client = 'Fundo Municipal de Saúde de Camboriú';
        $fechada = 'F';
        $desativada = 'F';
        $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,pagamento,fechada,desativada,entrega FROM ocorrencia WHERE cliente <> :cliente AND fechada = :fechada AND desativada = :desativada ORDER BY tecnico,hora,idocorrencia DESC");
        $sql->bindParam(':cliente', $client, PDO::PARAM_STR);
        $sql->bindParam(':fechada', $fechada, PDO::PARAM_STR);
        $sql->bindParam(':desativada', $desativada, PDO::PARAM_STR);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $pyidocorrencia = md5('idocorrencia');
                $pyserial = md5('serial');
                $dtA = '<table class="table table-bordered table-hover">';
                $dtB = '<table class="table table-bordered table-hover">';
                $dtC = '<table class="table table-bordered table-hover">';
                $dtD = '<table class="table table-bordered table-hover">';
                $dtE = '<table class="table table-bordered table-hover">';
                $dtF = '<table class="table table-bordered table-hover">';
                $dtG = '<table class="table table-bordered table-hover">';
                $dtH = '<table class="table table-bordered table-hover">';
                $dtI = '<table class="table table-bordered table-hover">';
                $dtJ = '<table class="table table-bordered table-hover">';
                $dtK = '<table class="table table-bordered table-hover">';
                $dtL = '<table class="table table-bordered table-hover">';
                $dtM = '<table class="table table-bordered table-hover">';
                $dtN = '<table class="table table-bordered table-hover">';
                $dtO = '<table class="table table-bordered table-hover">';
                $dtP = '<table class="table table-bordered table-hover">';

                    while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                        //INVERTENDO A DATA

                        $ano = substr($lin->datado,0,4);
                        $mes = substr($lin->datado,5,2);
                        $dia = substr($lin->datado,8);
                        $lin->datado = $dia."/".$mes."/".$ano;

                        //DADOS DO CLIENTE

                        $sql2 = $pdo->prepare("SELECT nome,endereco,bairro,cidade,telefone,celular,email FROM cliente WHERE nome = :cliente");
                        $sql2->bindParam(':cliente', $lin->cliente, PDO::PARAM_STR);
                        $sql2->execute();
                        $ret2 = $sql2->rowCount();

                            if($ret2 > 0) {
                                $lin2 = $sql2->fetch(PDO::FETCH_OBJ);
                                $cliente = $lin2->endereco.'<br>'.$lin2->bairro.' - '.$lin2->cidade.'<br>'.$lin2->telefone.' / '.$lin2->celular.'<br>'.$lin2->email;
                                unset($lin2);
                            }
                            else {
                                $cliente = 'O cliente n&atilde;o est&aacute; cadastrado.';
                            }

                        unset($sql2,$ret2);
                        /*$dba = dbase_open('clientes.dbf', 0);

                            if ($dba) {
                                $rec = dbase_numrecords($dba);

                                    for ($i = 1;$i <= $rec;$i++) {
                                        $row = dbase_get_record($dba,$i);
                                        $row[0] = trim($row[0]);

                                            if ($row[0] == utf8_decode($lin->cliente)) {
                                                $row[6] = utf8_encode($row[6]);
                                                $row[7] = utf8_encode($row[7]);
                                                $row[9] = utf8_encode($row[9]);
                                                $row[10] = utf8_encode($row[10]);
                                                $row[11] = utf8_encode($row[11]);
                                                $cliente = trim($row[6]).' '.trim($row[7]).', '.trim($row[8]).'<br>'.trim($row[9]).' - '.trim($row[10]).' - '.trim($row[11]).' - '.trim($row[12]).'<br>'.trim($row[14]).' - '.trim($row[15]).' - '.trim($row[20]);
                                            }
                                    }

                                if (empty($cliente)) {
                                    $cliente = 'N&atilde;o possui cadastro no AC';
                                }
                            }

                        dbase_close($dba);*/
                        //$cliente = '';

                        //VERIFICANDO SE A OCORRENCIA POSSUI ITENS CADASTRADOS

                        $sql2 = $pdo->prepare("SELECT idconta FROM conta WHERE idocorrencia = :idocorrencia");
                        $sql2->bindParam(':idocorrencia', $lin->idocorrencia, PDO::PARAM_INT);
                        $sql2->execute();
                        $ret2 = $sql2->rowCount();

                            if ($ret2 > 0) {
                                $imgitem = '<a title="Tabela de valores" href="valorOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'&'.$pyserial.'='.$lin->serial.'"><i class="fa fa-usd"></i></a>';
                            }
                            else {
                                $imgitem = '';
                            }

                        //SE A OCORRENCIA NAO ESTIVER CONCLUIDA E ENTREGUE EXIBI

                        if ($lin->fechada != 'T') {
                            if ($ano == date('Y')) {
                                if ($mes == date('m')) {
                                    $orgdata = $dia - date('d');

                                    // VERIFICA O RETORNO DO CLIENTE

                                    if ($lin->retorno == 'T') {
                                        $imgstatus = '<span><a title="Esperando retorno do cliente" href="#"><i class="fa fa-reply"></i></a></span>';
                                    }
                                    else {
                                        //VERIFICA SE ESTÁ FECHADA

                                        if ($lin->entrega == 'T') {
                                            $imgstatus = '
                                            <span><a title="Imprimir a ocorr&ecirc;ncia" href="printOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-print"></i></a></span>
                                            <!--<span><a title="O equipamento saiu para entrega" href="#"><i class="fa fa-truck"></i></a></span>-->';
                                        }
                                        else {
                                            $imgstatus = '';
                                        }
                                    }

                                    // VERIFICA O PAGAMENTO

                                    switch ($lin->pagamento) {
                                        case 'boleto': $lin->pagamento = 'Boleto'; break;
                                        case 'cartao': $lin->pagamento = 'Cart&atilde;o'; break;
                                        case 'contrato': $lin->pagamento = 'Contrato'; break;
                                        case 'dinheiro': $lin->pagamento = 'Dinheiro'; break;
                                        default: $lin->pagamento = 'Indefinido'; break;
                                    }

                                    switch($orgdata) {
                                        case -7:
                                            $dtA .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case -6:
                                            $dtB .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case -5:
                                            $dtC .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case -4:
                                            $dtD .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case -3:
                                            $dtE .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case -2:
                                            $dtF .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case -1:
                                            $dtG .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 0:
                                            $dtH .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 1:
                                            $dtI .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 2:
                                            $dtJ .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 3:
                                            $dtK .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 4:
                                            $dtL .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 5:
                                            $dtM .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 6:
                                            $dtN .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        case 7:
                                            $dtO .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                        default:
                                            $dtP .= '
                                            <tr>
                                                <td class="td-action-oc">
                                                    <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                                    <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                    <span>'.$imgitem.'</span>
                                                    <span>'.$imgstatus.'</span>
                                                    <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                                </td>
                                                <td class="td-action-time">'.$lin->datado.' - '.$lin->hora.' h - '.$lin->tecnico.'</td>
                                                <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                            </tr>

                                            <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                                <td colspan="3">
                                                    <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                                    <div>Procedimento: '.$lin->procedimento.'</div>
                                                    <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                                </td>
                                            </tr>';
                                            break;
                                    } // SWITCH
                                } // IF ($mes == date('m'))
                                else {
                                    // VERIFICA O RETORNO DO CLIENTE

                                    if ($lin->retorno == 'T') {
                                        $imgstatus = '<span><a title="Esperando retorno do cliente" href="#"><i class="fa fa-reply"></i></a></span>';
                                    }
                                    else {
                                        //VERIFICA SE ESTÁ FECHADA

                                        if ($lin->entrega == 'T') {
                                            $imgstatus = '
                                            <span><a title="Imprimir a ocorr&ecirc;ncia" href="printOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-print"></i></a></span>
                                            <!--<span><a title="O equipamento saiu para entrega" href="#"><i class="fa fa-truck"></i></a></span>-->';
                                        }
                                        else {
                                            $imgstatus = '';
                                        }
                                    }

                                    $dtP .= '
                                    <tr>
                                        <td class="td-action-oc">
                                            <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                            <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                            <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                            <span>'.$imgitem.'</span>
                                            <span>'.$imgstatus.'</span>
                                            <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                        </td>
                                        <td class="td-action-time">'.$lin->datado.' - '.$lin->hora.' h - '.$lin->tecnico.'</td>
                                        <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                    </tr>

                                    <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                        <td colspan="3">
                                            <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                            <div>Procedimento: '.$lin->procedimento.'</div>
                                            <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                        </td>
                                    </tr>';
                                }
                            } // IF ($ano == date('Y'))
                            else {
                                // VERIFICA O RETORNO DO CLIENTE

                                if ($lin->retorno == 'T') {
                                    $imgstatus = '<span><a title="Esperando retorno do cliente" href="#"><i class="fa fa-reply"></i></a></span>';
                                }
                                else {
                                    //VERIFICA SE ESTÁ FECHADA

                                    if ($lin->entrega == 'T') {
                                        $imgstatus = '
                                        <span><a title="Imprimir a ocorr&ecirc;ncia" href="printOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-print"></i></a></span>
                                        <!--<span><a title="O equipamento saiu para entrega" href="#"><i class="fa fa-truck"></i></a></span>-->';
                                    }
                                    else {
                                        $imgstatus = '';
                                    }
                                }

                                $dtP .= '
                                <tr>
                                    <td class="td-action-oc">
                                        <span><a class="open-oc" id="'.$lin->idocorrencia.'" title="Ver a ocorr&ecirc;ncia completa" href="#doc'.$lin->idocorrencia.'"><i class="fa fa-bars"></i></a></span>
                                        <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                        <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                        <span>'.$imgitem.'</span>
                                        <span>'.$imgstatus.'</span>
                                        <span><a title="'.$lin->pagamento.'" data-toggle="tooltip" data-placement="top" data-html="true" style="color: green;cursor: pointer;"><i class="fa fa-money"></i></a></span>
                                    </td>
                                    <td class="td-action-time">'.$lin->datado.' - '.$lin->hora.' h - '.$lin->tecnico.'</td>
                                    <td><strong><a href="#" title="'.$cliente.'" data-toggle="tooltip" data-placement="top" data-html="true">'.$lin->cliente.'</a></strong>: '.$lin->solicitacao.' <span class="pull-right span-serial"><strong>'.$lin->serial.'</strong></span></td>
                                </tr>

                                <tr class="dados-ocorrencia doc'.$lin->idocorrencia.'" style="display: none;">
                                    <td colspan="3">
                                        <div>Diagn&oacute;stico: '.$lin->diagnostico.'</div>
                                        <div>Procedimento: '.$lin->procedimento.'</div>
                                        <div>Observa&ccedil;&atilde;o: '.$lin->observacao.'</div>
                                    </td>
                                </tr>';
                            }
                        } // IF ($lin[11] != 'T')

                        unset($ano,$mes,$dia,$row,$rec,$dba,$res2,$ret2,$imgitem,$imgstatus,$orgdata);
                    } //WHILE

                    if ($dtO != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">EM 7 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtO.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtN != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">EM 6 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtN.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtM != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">EM 5 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtM.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtL != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">EM 4 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtL.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtK != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">EM 3 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtK.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtJ != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">DEPOIS DE AMANH&Atilde;</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtJ.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtI != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">AMANH&Atilde;</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtI.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtH != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-12 color-palette-set">
                                <span class="label label-warning">HOJE</span> <!--<a href="printOcorrenciaDia.php"><span class="label label-primary">Imprimir</span></a>--> <a class="pull-right" href="timeline.php"><span class="label label-warning">Linha do tempo</span></a>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtH.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtG != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">ONTEM</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtG.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtF != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">ANTES DE ONTEM</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtF.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtE != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">H&Aacute; 3 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtE.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtD != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">H&Aacute; 4 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtD.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtC != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">H&Aacute; 5 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtC.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtB != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">H&Aacute; 6 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtB.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtA != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">H&Aacute; 7 DIAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtA.'
                                </table>
                            </div>
                        </div>';
                    }

                    if ($dtP != '<table class="table table-bordered table-hover">') {
                        echo'
                        <div class="row">
                            <div class="col-md-2 color-palette-set">
                                <span class="label label-warning">OUTRAS DATAS</span>
                            </div>
                        </div>
                        <div class="spacing-row-1"></div>
                        <div class="row">
                            <div class="col-md-12">
                                    '.$dtP.'
                                </table>
                            </div>
                        </div>';
                    }

                unset($lin,$cliente,$pyidocorrencia,$pyserial,$dtA,$dtB,$dtC,$dtD,$dtE,$dtF,$dtG,$dtH,$dtI,$dtJ,$dtK,$dtL,$dtM,$dtN,$dtO,$dtP);
            } // IF ($ret > 0)
            else {
                echo'
                <div class="callout">
                    <h4>Nada encontrado</h4>
                    <p>Nenhum registro foi encontrado. <a class="link-new" data-toggle="modal" data-target="#nova-ocorrencia" title="Clique para abrir um nova ocorr&ecirc;ncia" href="#">Nova ocorr&ecirc;ncia</a></p>
                </div>';
            }

        unset($sql,$ret,$py,$fechada,$desativada);
    }
    catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }
?>
<script>
    $(document).ready(function () {
        var fade = 150;
        
        /* EXCLUIR NOTA */

        $(".delete-nota").click(function (e) {
            var click = this.id.split('-'), py = click[0], id = click[1];
            e.preventDefault();

            $.smkConfirm({
                text:'Quer mesmo excluir a nota?',
                accept:'Sim',
                cancel:'Não'
            },function (res) {
                // Code here
                if(res) {
                    location.href = 'deleteNota.php?' + py + '=' + id;
                }
            });
        });
        
        /* ABRIR OCORRENCIA */

        $(".open-oc").click(function () {
            $('.doc' + this.id).fadeToggle(fade);
            return true;
        });
        
        /* EXCLUIR OCORRENCIA */

        $(".delete-oc").click(function (e) {
            var click = this.id.split('-'), py = click[0], id = click[1];
            e.preventDefault();

            $.smkConfirm({
                text:'Quer mesmo excluir a ocorrência?',
                accept:'Sim',
                cancel:'Não'
            },function (res) {
                // Code here
                if(res) {
                    location.href = 'deleteOcorrencia.php?' + py + '=' + id;
                }
            });
        });
    });
</script>