<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 5;
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
                    <h1>Clientes <span class="pull-right lead"><a data-toggle="modal" data-target="#novo-cliente" title="Clique para cadastrar um novo cliente" href="#"><i class="fa fa-user"></i> Novo cliente</a></span></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            include_once('conexao.php');

                            try {
                                //BUSCANDO OS CLIENTE

                                $mostra = 'T';
                                $sql = $pdo->prepare("SELECT idcliente,nome,endereco,bairro,cidade,telefone,celular FROM cliente WHERE mostra = :mostra ORDER BY nome");
                                $sql->bindParam(':mostra', $mostra, PDO::PARAM_STR);
                                $sql->execute();
                                $ret = $sql->rowCount();

                                    if($ret > 0) {
                                        $pyidcliente = md5('idcliente');

                                        echo'
                                        <table class="table table-striped table-bordered table-hover table-data">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nome</th>
                                                    <th>Endere&ccedil;o</th>
                                                    <th>Telefone</th>
                                                    <th>Celular</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                echo'
                                                <tr>
                                                    <td class="td-action">
                                                        <span><a class="delete-cliente" id="'.$pyidcliente.'-'.$lin->idcliente.'" title="Excluir o cliente" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                        <span><a data-toggle="modal" data-target="#edita-cliente" title="Editar o cliente" href="editaCliente.php?'.$pyidcliente.'='.$lin->idcliente.'"><i class="fa fa-pencil"></i></a></span>
                                                    </td>
                                                    <td>'.$lin->nome.'</td>
                                                    <td>'.$lin->endereco.' - '.$lin->bairro.' - '.$lin->cidade.'</td>
                                                    <td>'.$lin->telefone.'</td>
                                                    <td>'.$lin->celular.'</td>
                                                </tr>';
                                            }

                                        echo'
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Nome</th>
                                                    <th>Endere&ccedil;o</th>
                                                    <th>Telefone</th>
                                                    <th>Celular</th>
                                                </tr>
                                            </tfoot>
                                        </table>';

                                        unset($lin,$pyidcliente);
                                    }
                                    else {
                                        echo'
                                        <div class="callout">
                                            <h4>Nada encontrado</h4>
                                            <p>Nenhum registro foi encontrado. <a class="link-new" data-toggle="modal" data-target="#novo-cliente" title="Clique para cadastrar um novo cliente" href="#">Novo cliente</a></p>
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

            <div class="modal fade" id="novo-cliente" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form class="form-novo-cliente">
                            <div class="modal-header">
                                <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Novo cliente <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                            </div>
                            <div class="modal-body overing">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pessoa"><i class="fa fa-asterisk"></i> Pessoa</label>
                                        <div class="input-group">
                                            <span class="form-icheck"><input type="radio" name="pessoa" id="fisica" value="F" checked> F&iacute;sica</span>
                                            <span class="form-icheck"><input type="radio" name="pessoa" id="juridica" value="J"> Jur&iacute;dica</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label-nome" for="nome-cliente"><i class="fa fa-asterisk"></i> Nome</label>
                                        <input type="text" id="nome-cliente" class="form-control" maxlength="255" title="Digite o nome do cliente" placeholder="Nome" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="label-nascimento" for="nascimento">Nascimento <cite class="msg-nascimento label label-danger"></cite></label>
                                        <div class="input-group col-md-4">
                                            <input type="text" id="nascimento" class="form-control" maxlength="10" title="Digite a data de nascimento" placeholder="Nascimento">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label-documento" for="cpf"><i class="fa fa-asterisk"></i> CPF <cite class="msg-documento label label-danger"></cite></label>
                                        <div class="input-group col-md-6">
                                            <input type="text" id="cpf" class="form-control" maxlength="14" title="Digite o CPF" placeholder="CPF" required>
                                            <input type="text" id="cnpj" class="form-control hide" maxlength="18" title="Digite o CNPJ" placeholder="CNPJ">
                                            <span class="help-block msg-cpf"></span>
                                            <span class="help-block msg-cnpj hide"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label-documento-2" for="rg">RG</label>
                                        <div class="input-group col-md-4">
                                            <input type="text" id="rg" class="form-control" maxlength="20" title="Digite o RG" placeholder="RG">
                                            <input type="text" id="ie" class="form-control hide" maxlength="20" title="Digite a IE" placeholder="IE">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <div class="input-group col-md-4">
                                            <input type="text" id="telefone" class="form-control" maxlength="13" title="Digite o telefone" placeholder="Telefone">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="celular">Celular</label>
                                        <div class="input-group col-md-4">
                                            <input type="text" id="celular" class="form-control" maxlength="13" title="Digite o celular" placeholder="Celular">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" class="form-control" maxlength="100" title="Digite o email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cep">CEP <cite class="msg-cep label label-danger"></cite></label>
                                        <div class="input-group col-md-4">
                                            <input type="text" id="cep" class="form-control" maxlength="9" title="Digite o CEP" placeholder="CEP">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="endereco">Endere&ccedil;o</label>
                                        <input type="text" id="endereco" class="form-control" maxlength="255" title="Digite o endere&ccedil;o" placeholder="Endere&ccedil;o, n&uacute;mero">
                                    </div>
                                    <div class="form-group">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" id="bairro" class="form-control" maxlength="100" title="Digite o bairro" placeholder="Bairro">
                                    </div>
                                    <div class="form-group">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" id="cidade" class="form-control" maxlength="100" title="Digite a cidade" placeholder="Cidade">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <div class="input-group col-md-4">
                                            <select id="estado" class="form-control">
                                                <option value="AC">AC</option>
                                                <option value="AL">AL</option>
                                                <option value="AM">AM</option>
                                                <option value="AP">AP</option>
                                                <option value="BA">BA</option>
                                                <option value="CE">CE</option>
                                                <option value="DF">DF</option>
                                                <option value="ES">ES</option>
                                                <option value="GO">GO</option>
                                                <option value="MA">MA</option>
                                                <option value="MG">MG</option>
                                                <option value="MS">MS</option>
                                                <option value="MT">MT</option>
                                                <option value="PA">PA</option>
                                                <option value="PB">PB</option>
                                                <option value="PE">PE</option>
                                                <option value="PI">PI</option>
                                                <option value="PR">PR</option>
                                                <option value="RJ">RJ</option>
                                                <option value="RN">RN</option>
                                                <option value="RO">RO</option>
                                                <option value="RR">RR</option>
                                                <option value="RS">RS</option>
                                                <option value="SC" selected>SC</option>
                                                <option value="SE">SE</option>
                                                <option value="SP">SP</option>
                                                <option value="TO">TO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="observacao">Observa&ccedil;&atilde;o</label>
                                        <textarea id="observacao-cliente" class="form-control" title="Digite a observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-novo-cliente">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="edita-cliente" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
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
        <script src="js/masked.min.js"></script>
        <script src="js/icheck.min.js"></script>
        <script src="js/datatables.min.js"></script>
        <script src="js/datatables.bootstrap.min.js"></script>
        <script src="js/core.min.js"></script>
    </body>
</html>
<?php unset($cfg,$rnd,$rnd2,$serial,$m); ?>
