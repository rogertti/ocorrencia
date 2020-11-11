<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 0;
    $pyocorrencia = md5('idocorrencia');
    $pyserial = md5('idserial');
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
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/datepicker.min.css">
        <link rel="stylesheet" href="css/icheck.min.css">
        <link rel="stylesheet" href="css/skin-blue.min.css">
        <link rel="stylesheet" href="css/core.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">
                <!-- Logo -->
                <a href="index2.html" class="logo">
                  <!-- mini logo for sidebar mini 50x50 pixels -->
                  <span class="logo-mini"><strong>O</strong>C</span>
                  <!-- logo for regular state and mobile devices -->
                  <span class="logo-lg"><strong>Ocorr</strong>&ecirc;ncia</span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li>
                                <a data-toggle="tooltip" data-placement="bottom" title="Sair" href="sair.php"><i class="fa fa-sign-out"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar"><?php #include_once('sidebar.php'); ?></aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Itens da ocorr&ecirc;ncia <?php echo $_GET[''.$pyserial.'']; ?> <span class="pull-right lead"><a data-toggle="modal" data-target="#novo-item" title="Clique para cadastrar um novo item" href="#"><i class="fa fa-file-text"></i> Novo item</a></span></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            include_once('conexao.php');

                            try {
                                //BUSCANDO OS ITENS

                                $descricao = '-';
                                $sql = $pdo->prepare("SELECT idconta,idocorrencia,descricao,quantidade,vunitario,vtotal,total,desconto FROM conta WHERE idocorrencia = :idocorrencia AND descricao <> :descricao ORDER BY descricao,quantidade,vunitario,total");
                                $sql->bindParam(':idocorrencia', $_GET[''.$pyocorrencia.''], PDO::PARAM_INT);
                                $sql->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                                $sql->execute();
                                $ret = $sql->rowCount();

                                    if($ret > 0) {
                                        $total = 0;
                                        $pyconta = md5('idconta');

                                        echo'
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th>Valor (R$)</th>
                                                    <th>Quantidade</th>
                                                    <th>Desconto (R$)</th>
                                                    <th>Subtotal (R$)</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                //TRATANDO OS VALORES

                                                if(strlen($lin->vunitario) <= 5) {
                                                    $lin->vunitario = number_format($lin->vunitario, 2, '.', ',');
                                                }

                                                if(strlen($lin->desconto) <= 5) {
                                                    $lin->desconto = number_format($lin->desconto, 2, '.', ',');
                                                }

                                                if(strlen($lin->vtotal) <= 5) {
                                                    $lin->vtotal = number_format($lin->vtotal, 2, '.', ',');
                                                }

                                                echo'
                                                <tr>
                                                    <td class="td-action">
                                                        <span><a class="delete-item" id="'.$pyconta.'-'.$lin->idconta.'-'.$pyocorrencia.'-'.$lin->idocorrencia.'-'.$pyserial.'-'.$_GET[''.$pyserial.''].'" title="Excluir o item da ocorr&ecirc;ncia" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                        <span><a data-toggle="modal" data-target="#edita-item" title="Editar o item da ocorr&ecirc;ncia" href="editaItem.php?'.$pyconta.'='.$lin->idconta.'&'.$pyocorrencia.'='.$lin->idocorrencia.'&'.$pyserial.'='.$_GET[''.$pyserial.''].'"><i class="fa fa-pencil"></i></a></span>
                                                    </td>
                                                    <td>'.$lin->descricao.'</td>
                                                    <td style="width: 120px;">'.$lin->vunitario.'</td>
                                                    <td style="width: 120px;">'.$lin->quantidade.'</td>
                                                    <td style="width: 120px;">'.$lin->desconto.'</td>
                                                    <td style="width: 120px;">'.$lin->vtotal.'</td>
                                                </tr>';

                                                 $total = $lin->vtotal + $total;
                                            }

                                            if(strlen($total) <= 5) {
                                                $total = number_format($total, 2, '.', ',');
                                            }

                                        echo'
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th>Valor (R$)</th>
                                                    <th>Quantidade</th>
                                                    <th>Desconto (R$)</th>
                                                    <th>Subtotal (R$)</th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="div-total">
                                            <p class="pull-left">
                                                <a class="btn btn-primary" title="Imprimir a ocorr&ecirc;ncia" href="printOcorrencia.php?'.$pyocorrencia.'='.$_GET[''.$pyocorrencia.''].'">Imprimir</a>
                                                <a class="btn btn-default" title="Fechar ocorr&ecirc;ncia e voltar para o in&iacute;cio" href="inicio.php">Concluir</a>
                                            </p>
                                            <p class="pull-right lead">Total R$ '.$total.'</p>
                                        </div>';

                                        unset($lin,$pyconta,$total);
                                    }
                                    else {
                                        echo'
                                        <div class="callout">
                                            <h4>Nada encontrado</h4>
                                            <p>Nenhum registro foi encontrado. <a class="link-new" data-toggle="modal" data-target="#novo-item" title="Clique para cadastrar um novo item" href="#">Novo item</a></p>
                                        </div>

                                        <div class="div-total">
                                            <p class="pull-left">
                                                <a class="btn btn-default" title="Fechar ocorr&ecirc;ncia e voltar para o in&iacute;cio" href="inicio.php">Concluir</a>
                                            </p>
                                        </div>';
                                    }

                                unset($sql,$ret,$descricao);
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
            <div class="modal fade" id="novo-item" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-novo-item">
                            <div class="modal-header">
                                <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Novo item <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="idocorrencia" class="form-control" value="<?php echo $_GET[''.$pyocorrencia.'']; ?>">

                                <div class="form-group">
                                    <label for="descricao"><i class="fa fa-asterisk"></i> Descri&ccedil;&atilde;o</label>
                                    <input type="text" id="descricao" class="form-control" maxlength="255" title="Digite a descri&ccedil;&atilde;o do item" placeholder="Descri&ccedil;&atilde;o" required>
                                </div>
                                <div class="form-group">
                                    <label for="valor"><i class="fa fa-asterisk"></i> Valor (R$)</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="valor" class="form-control" maxlength="10" title="Digite a descri&ccedil;&atilde;o do item" placeholder="Valor" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="quantidade"><i class="fa fa-asterisk"></i> Quantidade</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="quantidade" class="form-control" maxlength="3" title="Digite a quantidade do item" placeholder="Quantidade" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desconto">Desconto (R$)</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="desconto" class="form-control" maxlength="10" title="Desconto do item" placeholder="Desconto">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="subtotal"><i class="fa fa-asterisk"></i> Subtotal (R$)</label>
                                    <div class="input-group col-md-4">
                                        <input type="text" id="subtotal" class="form-control" maxlength="10" title="Subtotal do item" placeholder="Subtotal" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-novo-item">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="edita-item" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div><!-- ./modal -->

            <!-- Main Footer -->
            <footer class="main-footer"><?php include_once('footer.php'); ?></footer>
        </div><!-- ./wrapper -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/smoke.min.js"></script>
        <script src="js/datepicker.min.js"></script>
        <script src="js/icheck.min.js"></script>
        <script src="js/maskmoney.min.js"></script>
        <script src="js/core.min.js"></script>
    </body>
</html>
<?php unset($m,$pyocorrencia,$pyserial); ?>
