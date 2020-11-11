<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 0;
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
        <link rel="stylesheet" href="css/skin-blue.min.css">
        <link rel="stylesheet" href="css/core.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body>
        <div class="wrapper">
        <?php
            try {
                include_once('conexao.php');

                /* BUSCANDO OS DADOS DO RECIBO */

                $py = md5('idrecibo');
                $sql = $pdo->prepare("SELECT idrecibo,cliente,serial,datado,hora,valor,receptor FROM recibo WHERE idrecibo = :idrecibo");
                $sql->bindParam(':idrecibo', $_GET[''.$py.''], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $lin = $sql->fetch(PDO::FETCH_OBJ);

                        //INVERTENDO A DATA

                        $ano = substr($lin->datado,0,4);
                        $mes = substr($lin->datado,5,2);
                        $dia = substr($lin->datado,8);
                        $lin->datado = $dia."/".$mes."/".$ano;

                        //TRATANDO OS VALORES

                        if(strlen($lin->valor) <= 5) {
                            $lin->valor = number_format($lin->valor, 2, '.', ',');
                        }
        ?>
            <!-- Main content -->
            <section class="invoice">
                <div class="row print-head">
                    <div class="col-xs-12">
                        <h2 class="page-header">
                            <span class="logo"><img src="img/logo.png" title="Embracore, Ltda." alt="Embracore, Ltda." style="height: 120px;"></span>
                            <div class="pull-right text-right">
                                <small><i class="fa fa-globe"></i> <?php echo $cfg['empresa']; ?></small><br>
                                <small><?php echo $cfg['endereco1']; ?></small><br>
                                <small><?php echo $cfg['endereco2']; ?></small><br>
                                <small><?php echo $cfg['telefone1'].' - '.$cfg['telefone2']; ?></small><br>
                                <!--<small><?php echo $cfg['telefone2']; ?></small><br>-->
                                <small><?php echo $cfg['site']; ?></small>
                            </div>
                        </h2>
                    </div>
                </div><!-- /.print-head -->
                <div class="row print-title">
                    <div class="col-xs-12 text-center">
                        <h1 class="text-uppercase">Recibo <?php echo $lin->serial; ?></h1>
                        <span class="lead"><?php echo $lin->datado.' - '.$lin->hora.' h - '.$lin->receptor; ?></span>
                        <p class="lead"><strong><?php echo 'R$ '.$lin->valor; ?></strong></p>
                    </div>
                </div><!-- /.print-title -->
                <?php
                    $sql2 = $pdo->prepare("SELECT nome,cpf_cnpj,rg_ie,endereco,bairro,cidade,estado,telefone,celular,email FROM cliente WHERE nome = :cliente");
                    $sql2->bindParam(':cliente', $lin->cliente, PDO::PARAM_STR);
                    $sql2->execute();
                    $ret2 = $sql2->rowCount();

                        if($ret2 > 0) {
                            $lin2 = $sql2->fetch(PDO::FETCH_OBJ);

                            echo'
                            <div class="row invoice-info print-client">
                                <div class="col-sm-6 invoice-col">
                                    <p>'.$lin2->nome.'</p>
                                    <p>'.$lin2->endereco.' - '.$lin2->bairro.' - '.$lin2->cidade.' - '.$lin2->estado.'</p>
                                </div>
                                <div class="col-sm-6 invoice-col">
                                    <p>'.$lin2->cpf_cnpj.' - '.$lin2->rg_ie.'</p>
                                    <p>'.$lin2->telefone.' / '.$lin2->celular.'</p>
                                </div>
                            </div><!-- /.print-client -->';

                            unset($lin2);
                        }
                        else {
                            echo'
                            <div class="row invoice-info print-client">
                                <div class="col-sm-6 invoice-col">
                                    <p>'.$lin->cliente.'</p>
                                    <p></p>
                                </div>
                                <div class="col-sm-6 invoice-col">
                                    <p></p>
                                    <p></p>
                                </div>
                            </div><!-- /.print-client -->';
                        }

                    unset($sql2,$ret2);
                    /*$dba = dbase_open('clientes.dbf',0);

                        if ($dba) {
                            $rec = dbase_numrecords($dba);

                                for ($i = 1;$i <= $rec;$i++) {
                                    $row = dbase_get_record($dba,$i);
                                    $row[0] = trim($row[0]);

                                        if ($row[0] == utf8_decode($lin->cliente)) {
                                            $row[0] = utf8_encode($row[0]);
                                            $row[6] = utf8_encode($row[6]);
                                            $row[7] = utf8_encode($row[7]);
                                            $row[10] = utf8_encode($row[10]);
                                            $row[11] = utf8_encode($row[11]);
                                            $cliente = $lin->cliente;

                                            echo'
                                            <div class="row invoice-info print-client">
                                                <div class="col-sm-6 invoice-col">
                                                    <p>'.trim($row[0]).'</p>
                                                    <p>'.trim($row[6]).' '.trim($row[7]).', '.trim($row[8]).' - '.trim($row[10]).' - '.trim($row[11]).'</p>
                                                </div>
                                                <div class="col-sm-6 invoice-col">
                                                    <p>'.trim($row[5]).' - '.trim($row[3]).'</p>
                                                    <p>'.trim($row[14]).' / '.trim($row[20]).'</p>
                                                </div>
                                            </div><!-- /.print-client -->';
                                        }
                                }

                                if(empty($cliente)) {
                                    echo'
                                    <div class="row invoice-info print-client">
                                        <div class="col-sm-6 invoice-col">
                                            <p>'.$lin->cliente.'</p>
                                            <p></p>
                                        </div>
                                        <div class="col-sm-6 invoice-col">
                                            <p></p>
                                            <p></p>
                                        </div>
                                    </div><!-- /.print-client -->';
                                }

                            unset($rec,$i,$cliente);
                        }

                    dbase_close($dba);
                    unset($dba,$row);*/
                ?>
                <div class="row invoice-info print-billet">
                    <div class="col-xs-12">
                        <p>
                            <span class="pull-right">Assinatura: ________________________________________________________</span>
                        </p>
                    </div>
                </div><!-- /.print-billet -->
            </section><!-- ./invoice -->
        <?php
                        unset($lin,$ano,$mes,$dia);
                    }//if($ret > 0)
                    else {
                        echo'
                        <div class="callout">
                            <h4>Par&acirc;mentro incorreto</h4>
                        </div>';
                    }

                unset($pdo,$sql,$py);
            }
            catch(PDOException $e) {
                echo 'Erro ao conectar o servidor '.$e->getMessage();
            }
        ?>
        </div><!-- ./wrapper -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/core.min.js"></script>
        <script>
            $(function() {
                /* PRINT */

                <?php if(!empty($ret)) { ?>
                    $(window).load(function () {
                        window.onafterprint = function(e){
                            $(window).off('mousemove', window.onafterprint);
                            console.log('Print Dialog Closed..');
                            location.href = 'recibo.php';
                        };

                        window.print();

                        setTimeout(function(){
                            $(window).on('mousemove', window.onafterprint);
                        }, 1);
                    });
                <?php } ?>
            });
        </script>
    </body>
</html>
<?php unset($cfg,$ret); ?>
