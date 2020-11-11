<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 4;
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
                    <h1>Curr&iacute;culos</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            include_once('conexao.php');

                            try {
                                //BUSCANDO OS CURRICULOS

                                $sql = $pdo->prepare("SELECT idcurriculo,nome,email,link FROM curriculo ORDER BY nome,email");
                                $sql->execute();
                                $ret = $sql->rowCount();

                                    if($ret > 0) {
                                        $pyidcurriculo = md5('idcurriculo');

                                        echo'
                                        <table class="table table-striped table-bordered table-hover table-data">
                                            <thead>
                                                <tr>
                                                    <!--<th></th>-->
                                                    <th>Nome</th>
                                                    <th>Email</th>
                                                    <th>Arquivo</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                //INVERTENDO A DATA

                                                $ano = substr($lin->datado,0,4);
                                                $mes = substr($lin->datado,5,2);
                                                $dia = substr($lin->datado,8);
                                                $lin->datado = $dia."/".$mes."/".$ano;

                                                echo'
                                                <tr>
                                                    <!--<td class="td-action">
                                                        <span><a class="delete-curriculo" id="'.$pyidcurriculo.'-'.$lin->idcurriculo.'" title="Excluir o curr&iacute;culo" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    </td>-->
                                                    <td>'.$lin->nome.'</td>
                                                    <td>'.$lin->email.'</td>
                                                    <td><a target="_blank" title="Abrir o curr&iacute;culo" href="http://www.embracore.com.br/site/curriculo/'.$lin->link.'">'.$lin->link.'</a></td>
                                                </tr>';

                                                unset($dia,$mes,$ano);
                                            }

                                        echo'
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <!--<th></th>-->
                                                    <th>Nome</th>
                                                    <th>Email</th>
                                                    <th>Arquivo</th>
                                                </tr>
                                            </tfoot>
                                        </table>';

                                        unset($lin,$pyidcurriculo);
                                    }
                                    else {
                                        echo'
                                        <div class="callout">
                                            <h4>Nada encontrado</h4>
                                            <p>Nenhum registro foi encontrado.</p>
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
            <!-- ./modal -->

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
        <script src="js/core.min.js"></script>
    </body>
</html>
<?php unset($cfg,$rnd,$rnd2,$serial,$m); ?>
