<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 1;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo $cfg['titulo']; ?></title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <link rel="stylesheet" href="css/autocomplete.min.css">
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/datepicker.min.css">
        <link rel="stylesheet" href="css/icheck.min.css">
        <link rel="stylesheet" href="css/datatables.min.css">
        <link rel="stylesheet" href="css/skin-blue.min.css">
        <link rel="stylesheet" href="css/core.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header"><?php include_once('header.php'); ?></header>

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar"><?php include_once('sidebar.php'); ?></aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Linha do tempo <span class="pull-right lead"><a data-toggle="modal" data-target="#nova-ocorrencia" title="Clique para abrir uma nova ocorr&ecirc;ncia" href="#"><i class="fa fa-file-text"></i> Nova ocorr&ecirc;ncia</a></span></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                        <?php
                            include_once('conexao.php');

                            try {
                                //BUSCANDO AS OCORRENCIAS

                                $datado = date('Y-m-d');
                                $fechada = 'F';
                                $desativada = 'F';
                                $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,fechada,desativada,entrega FROM ocorrencia WHERE datado = :datado AND fechada = :fechada AND desativada = :desativada ORDER BY hora DESC, tecnico");
                                $sql->bindParam(':datado', $datado, PDO::PARAM_STR);
                                $sql->bindParam(':fechada', $fechada, PDO::PARAM_STR);
                                $sql->bindParam(':desativada', $desativada, PDO::PARAM_STR);
                                $sql->execute();
                                $ret = $sql->rowCount();

                                    if($ret > 0) {
                                        $pyidocorrencia = md5('idocorrencia');

                                        echo'
                                        <ul class="timeline">
                                            <!-- timeline time label -->
                                            <li class="time-label">
                                                <span class="bg-blue text-uppercase">hoje - '.date('d/m/Y').'</span>
                                            </li>
                                            <!-- /.timeline-label -->';

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
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

                                                // VERIFICA O RETORNO DO CLIENTE

                                                if ($lin->retorno == 'T') {
                                                    $imgstatus = '<span><a title="Esperando retorno do cliente" href="#"><i class="fa fa-reply"></i></a></span>';
                                                }
                                                else {
                                                    //VERIFICA SE ESTÁ FECHADA

                                                    if ($lin->entrega == 'T') {
                                                        $imgstatus = '
                                                        <span><a title="Imprimir a ocorr&ecirc;ncia" href="printOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-print"></i></a></span>
                                                        <span><a title="O equipamento saiu para entrega" href="#"><i class="fa fa-truck"></i></a></span>';
                                                    }
                                                    else {
                                                        $imgstatus = '';
                                                    }
                                                }

                                                unset($sql2,$ret2);

                                                echo'
                                                <li>
                                                    <i class="fa fa-file-text bg-gray"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fa fa-clock-o"></i> '.$lin->hora.' h - '.$lin->tecnico.'</span>
                                                        <h3 class="timeline-header"><a class="lead">'.$lin->serial.'</a> - '.$lin->cliente.'</h3>
                                                        <div class="timeline-body">'.$lin->solicitacao.'</div>
                                                        <div class="timeline-footer text-right">
                                                            <span><a class="delete-oc" id="'.$pyidocorrencia.'-'.$lin->idocorrencia.'" title="Excluir a ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                            <span><a data-toggle="modal" data-target="#edita-ocorrencia" title="Editar a ocorr&ecirc;ncia" href="editaOcorrencia.php?'.$pyidocorrencia.'='.$lin->idocorrencia.'"><i class="fa fa-pencil"></i></a></span>
                                                            <span>'.$imgitem.'</span>
                                                            <span>'.$imgstatus.'</span>
                                                        </div>
                                                    </div>
                                                </li>';
                                            }

                                        echo'
                                            <li>
                                                <i class="fa fa-clock-o bg-gray"></i>
                                            </li>
                                        </ul>';

                                        unset($lin,$pyidocorrencia);
                                    }
                                    else {
                                        echo'
                                        <div class="callout">
                                            <h4>Nada encontrado</h4>
                                            <p>Nenhum registro foi encontrado. <a class="link-new" data-toggle="modal" data-target="#novo-ocorrencia" title="Clique para cadastrar um nova ocorr&ecirc;ncia" href="#">Nova ocorr&ecirc;ncia</a></p>
                                        </div>';
                                    }

                                unset($sql,$ret,$desativada,$fechada,$datado);
                            }
                            catch(PDOException $e) {
                                echo 'Erro ao conectar o servidor '.$e->getMessage();
                            }
                        ?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <!-- Modal -->
            <div class="modal fade" id="nova-ocorrencia" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form class="form-nova-ocorrencia">
                        <?php
                            //GERANDO O SERIAL

                            $rnd = substr(md5(rand()),0,2);
                            $rnd2 = substr(md5(rand()),2,2);
                            $serial = md5(rand());
                            $serial = base64_encode($serial);
                            $serial = substr($serial,0,2);
                            $rnd = strtoupper($rnd);
                            $rnd2 = strtoupper($rnd2);
                            $serial = strtoupper($serial);
                            $serial = $serial.date('dm').$rnd2.$rnd;
                        ?>
                            <div class="modal-header">
                                <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Nova ocorr&ecirc;ncia <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small> <span class="pull-right lead"><strong><?php echo $serial; ?></strong></span></h4>
                            </div>
                            <div class="modal-body overing">
                                <div class="col-md-6">
                                    <input type="hidden" id="serial" class="form-control" value="<?php echo $serial; ?>">

                                    <div class="form-group">
                                        <label for="cliente"><i class="fa fa-asterisk"></i> Cliente <cite class="msg-cliente label label-danger"></cite></label>
                                        <input type="hidden" id="idcliente" class="form-control">
                                        <input type="text" id="cliente" class="form-control" maxlength="255" title="Digite o nome do cliente" placeholder="Cliente" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
                                        <div class="input-group col-md-6">
                                            <input type="text" id="datado" class="form-control" maxlength="10" value="<?php echo date('d/m/Y'); ?>" title="Digite a data" placeholder="Data" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="hora"><i class="fa fa-asterisk"></i> Hora</label>
                                        <div class="input-group col-md-6">
                                            <select id="hora" class="form-control" required>
                                                <option value="" selected>Selecione a hora</option>
                                                <option value="08:00">08:00</option>
                                                <option value="08:30">08:30</option>
                                                <option value="09:00">09:00</option>
                                                <option value="09:30">09:30</option>
                                                <option value="10:00">10:00</option>
                                                <option value="10:30">10:30</option>
                                                <option value="11:00">11:00</option>
                                                <option value="11:30">11:30</option>
                                                <option value="13:30">13:30</option>
                                                <option value="14:00">14:00</option>
                                                <option value="14:30">14:30</option>
                                                <option value="15:00">15:00</option>
                                                <option value="15:30">15:30</option>
                                                <option value="16:00">16:00</option>
                                                <option value="16:30">16:30</option>
                                                <option value="17:00">17:00</option>
                                                <option value="17:30">17:30</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-icheck">
                                        <div class="form-group">
                                            <label for="entrega"><i class="fa fa-asterisk"></i> Entregar</label>
                                            <div class="input-group">
                                                <span class="form-icheck"><input type="radio" name="entrega" id="onentrega" value="T"> Sim</span>
                                                <span class="form-icheck"><input type="radio" name="entrega" id="offentrega" value="F" checked> N&atilde;o</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fechada"><i class="fa fa-asterisk"></i> Concluir</label>
                                            <div class="input-group">
                                                <span class="form-icheck"><input type="radio" name="fechada" id="onfechada" value="T"> Sim</span>
                                                <span class="form-icheck"><input type="radio" name="fechada" id="offfechada" value="F" checked> N&atilde;o</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="retorno"><i class="fa fa-asterisk"></i> Retorno</label>
                                            <div class="input-group">
                                                <span class="form-icheck"><input type="radio" name="retorno" id="onretorno" value="T"> Sim</span>
                                                <span class="form-icheck"><input type="radio" name="retorno" id="offretorno" value="F" checked> N&atilde;o</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tecnico"><i class="fa fa-asterisk"></i> T&eacute;cnico</label>
                                        <div class="input-group col-md-6">
                                            <select id="tecnico" class="form-control" required>
                                                <option value="" selected>Selecione o t&eacute;cnico</option>
                                                <?php
                                                    try {
                                                        $sql = $pdo->prepare("SELECT nome FROM tecnico ORDER BY nome");
                                                        $sql->execute();
                                                        $ret = $sql->rowCount();

                                                            if($ret > 0) {
                                                                while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                                    echo'<option value="'.$lin->nome.'">'.$lin->nome.'</option>';
                                                                }

                                                                unset($lin);
                                                            }

                                                        unset($sql,$ret);
                                                    }
                                                    catch(PDOException $e) {
                                                        echo 'Erro ao conectar o servidor '.$e->getMessage();
                                                    }
                                                ?>
                                            </select>
                                            <span class="input-group-addon">
                                                <a data-toggle="modal" data-target="#novo-tecnico" href="#"><i class="fa fa-plus fa-fw"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="solicitacao"><i class="fa fa-asterisk"></i> Solicita&ccedil;&atilde;o</label>
                                        <textarea id="solicitacao" class="form-control" title="Digite a solicita&ccedil;&atilde;o" placeholder="Solicita&ccedil;&atilde;o" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="diagnostico">Diagn&oacute;stico</label>
                                        <textarea id="diagnostico" class="form-control" title="Digite o diagn&oacute;stico" placeholder="Diagn&oacute;stico"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="procedimento">Procedimento</label>
                                        <textarea id="procedimento" class="form-control" title="Digite o procedimento" placeholder="Procedimento"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="observacao">Observa&ccedil;&atilde;o</label>
                                        <textarea id="observacao" class="form-control" title="Digite a observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-nova-ocorrencia">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="nova-nota" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-nova-nota">
                            <div class="modal-header">
                                <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Nova nota <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
                                    <div class="input-group col-md-3">
                                        <input type="text" id="datado-nota" class="form-control" maxlength="10" value="<?php echo date('d/m/Y'); ?>" title="Digite a data" placeholder="Data" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tecnico"><i class="fa fa-asterisk"></i> T&eacute;cnico</label>
                                    <div class="input-group col-md-6">
                                        <select id="tecnico-nota" class="form-control" required>
                                            <option value="" selected>Selecione o t&eacute;cnico</option>
                                            <?php
                                                try {
                                                    $sql = $pdo->prepare("SELECT nome FROM tecnico ORDER BY nome");
                                                    $sql->execute();
                                                    $ret = $sql->rowCount();

                                                        if($ret > 0) {
                                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                                echo'<option value="'.$lin->nome.'">'.$lin->nome.'</option>';
                                                            }

                                                            unset($lin);
                                                        }

                                                    unset($pdo,$sql,$ret);
                                                }
                                                catch(PDOException $e) {
                                                    echo 'Erro ao conectar o servidor '.$e->getMessage();
                                                }
                                            ?>
                                        </select>
                                        <span class="input-group-addon">
                                            <a data-toggle="modal" data-target="#novo-tecnico" href="#"><i class="fa fa-plus fa-fw"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="texto"><i class="fa fa-asterisk"></i> Texto</label>
                                    <textarea id="texto" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-nova-nota">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="novo-tecnico" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-novo-tecnico">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Novo t&eacute;cnico <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nome"><i class="fa fa-asterisk"></i> Nome</label>
                                    <input type="text" id="nome" class="form-control" maxlength="255" title="Digite o nome do t&eacute;cnico" placeholder="Nome" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-novo-tecnico">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="edita-ocorrencia" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content"></div>
                </div>
            </div>

            <!-- Main Footer -->
            <footer class="main-footer"><?php include_once('footer.php'); ?></footer>
        </div><!-- ./wrapper -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/autocomplete.min.js"></script>
        <script src="js/smoke.min.js"></script>
        <script src="js/datepicker.min.js"></script>
        <script src="js/masked.min.js"></script>
        <script src="js/icheck.min.js"></script>
        <script src="js/datatables.min.js"></script>
        <script src="js/datatables.bootstrap.min.js"></script>
        <script src="js/core.min.js"></script>
    </body>
</html>
<?php unset($cfg,$rnd,$rnd2,$serial,$m); ?>
