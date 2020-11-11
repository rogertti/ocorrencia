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

                /* BUSCANDO OS DADOS DA OCORRENCIA */

                $pyocorrencia = md5('idocorrencia');
                $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico FROM ocorrencia WHERE idocorrencia = :idocorrencia");
                $sql->bindParam(':idocorrencia', $_GET[''.$pyocorrencia.''], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $lin = $sql->fetch(PDO::FETCH_OBJ);

                        //INVERTENDO A DATA

                        $ano = substr($lin->datado,0,4);
                        $mes = substr($lin->datado,5,2);
                        $dia = substr($lin->datado,8);
                        $lin->datado = $dia."/".$mes."/".$ano;
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
                        <h1 class="text-uppercase">Ocorr&ecirc;ncia <?php echo $lin->serial; ?></h1>
                        <span class="lead"><?php echo $lin->datado.' - '.$lin->hora.' h - '.$lin->tecnico; ?></span>
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
                <div class="row invoice-info print-event">
                    <div class="col-xs-12">
                        <p><strong>Solicita&ccedil;&atilde;o:</strong> <?php echo $lin->solicitacao; ?></p>
                        <!--<p><strong>Diagn&oacute;stico:</strong> <?php echo $lin->diagnostico; ?></p>
                        <p><strong>Procedimento:</strong> <?php echo $lin->procedimento; ?></p>
                        <p><strong>Observa&ccedil;&atilde;o:</strong> <?php echo $lin->observacao; ?></p>-->
                    </div>
                </div><!-- /.print-event -->
                <?php
                    /*$descricao = '-';
                    $sql2 = $pdo->prepare("SELECT idconta,idocorrencia,descricao,quantidade,vunitario,vtotal,total,desconto FROM conta WHERE idocorrencia = :idocorrencia AND descricao <> :descricao ORDER BY descricao,quantidade,vunitario,total");
                    $sql2->bindParam(':idocorrencia', $lin->idocorrencia, PDO::PARAM_INT);
                    $sql2->bindParam(':descricao', $lin->idocorrencia, PDO::PARAM_STR);
                    $sql2->execute();
                    $ret2 = $sql2->rowCount();

                        if($ret2 > 0) {
                            $total = 0;

                            echo'
                            <div class="row print-pay">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Descri&ccedil;&atilde;o</th>
                                                <th>Valor (R$)</th>
                                                <th>Quantidade</th>
                                                <th>Desconto (R$)</th>
                                                <th>Subtotal (R$)</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

                                while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                    //TRATANDO OS VALORES

                                    if(strlen($lin2->vunitario) <= 5) {
                                        $lin2->vunitario = number_format($lin2->vunitario, 2, '.', ',');
                                    }

                                    if(strlen($lin2->desconto) <= 5) {
                                        $lin2->desconto = number_format($lin2->desconto, 2, '.', ',');
                                    }

                                    if(strlen($lin2->vtotal) <= 5) {
                                        $lin2->vtotal = number_format($lin2->vtotal, 2, '.', ',');
                                    }

                                    echo'
                                    <tr>
                                        <td>'.$lin2->descricao.'</td>
                                        <td style="width: 120px;">'.$lin2->vunitario.'</td>
                                        <td style="width: 120px;">'.$lin2->quantidade.'</td>
                                        <td style="width: 120px;">'.$lin2->desconto.'</td>
                                        <td style="width: 120px;">'.$lin2->vtotal.'</td>
                                    </tr>';

                                    $total = $lin2->vtotal + $total;
                                }

                                if(strlen($total) <= 5) {
                                    $total = number_format($total, 2, '.', ',');
                                }

                            echo'
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="lead text-right">Total R$ '.$total.'</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div><!-- /.print-pay -->';

                            unset($lin2,$total);
                        }//if($ret2 > 0)

                    unset($sql2,$ret2,$descricao);*/
                ?>
                <!--<div class="row invoice-info print-billet">
                    <div class="col-xs-12">
                        <p>
                            <span>O boleto foi entregue para o cliente? (&nbsp;&nbsp;) Sim (&nbsp;&nbsp;) N&atilde;o</span>
                            <span class="pull-right">Assinatura: _______________________________________</span>
                        </p>
                    </div>
                </div> /.print-billet -->
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

                unset($pdo,$sql);
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
                            location.href = 'inicio.php';
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
<?php unset($cfg,$ret,$pyocorrencia); ?>