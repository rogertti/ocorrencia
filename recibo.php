<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 3;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo $cfg['titulo']; ?></title>
        <link rel="icon" type="image/png" href="img/favicon.png">
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
                    <h1>Recibos <span class="pull-right lead"><a data-toggle="modal" data-target="#novo-recibo" title="Clique para cadastrar um novo recibo" href="#"><i class="fa fa-credit-card"></i> Novo recibo</a></span></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            include_once('conexao.php');

                            try {
                                //BUSCANDO OS RECIBOS

                                $mostra = 'T';
                                $sql = $pdo->prepare("SELECT idrecibo,cliente,serial,datado,hora,valor,receptor FROM recibo WHERE mostra = :mostra ORDER BY cliente,datado,valor,receptor");
                                $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
                                $sql->execute();
                                $ret = $sql->rowCount();

                                    if($ret > 0) {
                                        $pyidrecibo = md5('idrecibo');

                                        echo'
                                        <table class="table table-striped table-bordered table-hover table-data">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Cliente</th>
                                                    <th>Serial</th>
                                                    <th>Data</th>
                                                    <th>Valor (R$)</th>
                                                    <th>Receptor</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                //INVERTENDO A DATA

                                                $ano = substr($lin->datado,0,4);
                                                $mes = substr($lin->datado,5,2);
                                                $dia = substr($lin->datado,8);
                                                $lin->datado = $dia."/".$mes."/".$ano;

                                                //TRATANDO OS VALORES

                                                if(strlen($lin->valor) <= 5) {
                                                    $lin->valor = number_format($lin->valor, 2, '.', ',');
                                                }

                                                echo'
                                                <tr>
                                                    <td class="td-action">
                                                        <span><a class="delete-recibo" id="'.$pyidrecibo.'-'.$lin->idrecibo.'" title="Excluir o recibo" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                        <span><a data-toggle="modal" data-target="#edita-recibo" title="Editar o recibo" href="editaRecibo.php?'.$pyidrecibo.'='.$lin->idrecibo.'"><i class="fa fa-pencil"></i></a></span>
                                                        <span><a title="Imprimir o recibo" href="printRecibo.php?'.$pyidrecibo.'='.$lin->idrecibo.'"><i class="fa fa-print"></i></a></span>
                                                    </td>
                                                    <td>'.$lin->cliente.'</td>
                                                    <td>'.$lin->serial.'</td>
                                                    <td>'.$lin->datado.'</td>
                                                    <td>'.$lin->valor.'</td>
                                                    <td>'.$lin->receptor.'</td>
                                                </tr>';

                                                unset($dia,$mes,$ano);
                                            }

                                        echo'
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Cliente</th>
                                                    <th>Serial</th>
                                                    <th>Data</th>
                                                    <th>Valor (R$)</th>
                                                    <th>Receptor</th>
                                                </tr>
                                            </tfoot>
                                        </table>';

                                        unset($lin,$pyidrecibo);
                                    }
                                    else {
                                        echo'
                                        <div class="callout">
                                            <h4>Nada encontrado</h4>
                                            <p>Nenhum registro foi encontrado. <a class="link-new" data-toggle="modal" data-target="#novo-recibo" title="Clique para cadastrar um novo recibo" href="#">Novo recibo</a></p>
                                        </div>';
                                    }

                                unset($sql,$ret,$mostra);
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
            <?php
                include_once('modalNovaOcorrencia.php');
                include_once('modalNovaNota.php');
                include_once('modalNovoTecnico.php');
            ?>

            <div class="modal fade" id="novo-recibo" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-novo-recibo">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Novo recibo <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="hora-recibo" class="form-control" value="<?php echo date('H:i:s'); ?>">

                                <div class="form-group">
                                    <label for="cliente"><i class="fa fa-asterisk"></i> Cliente</label>
                                    <input type="text" id="cliente-recibo" class="form-control" maxlength="255" title="Digite o nome do cliente" placeholder="Cliente" required>
                                </div>
                                <div class="form-group">
                                    <label for="serial"><i class="fa fa-asterisk"></i> Serial</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="serial-recibo" class="form-control" maxlength="10" title="Digite o n&uacute;mero de serial da ocorr&ecirc;ncia" placeholder="Serial" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="datado-recibo" class="form-control" maxlength="10" title="Digite a data" placeholder="Datado" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="valor"><i class="fa fa-asterisk"></i> Valor</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="valor" class="form-control" maxlength="10" title="Digite o valor do recibo" placeholder="Valor" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="receptor"><i class="fa fa-asterisk"></i> Receptor</label>
                                    <div class="input-group col-md-6">
                                        <select id="receptor" class="form-control" required>
                                            <option value="" selected>Selecione o receptor</option>
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-novo-recibo">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="edita-recibo" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div><!-- ./modal -->

            <!-- Main Footer -->
            <footer class="main-footer"><?php include_once('footer.php'); ?></footer>
        </div><!-- ./wrapper -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/autocomplete.min.js"></script>
        <script src="js/smoke.min.js"></script>
        <script src="js/datepicker.min.js"></script>
        <script src="js/icheck.min.js"></script>
        <script src="js/datatables.min.js"></script>
        <script src="js/datatables.bootstrap.min.js"></script>
        <script src="js/maskmoney.min.js"></script>
        <script src="js/core.min.js"></script>
    </body>
</html>
<?php unset($cfg,$rnd,$rnd2,$serial,$m); ?>
