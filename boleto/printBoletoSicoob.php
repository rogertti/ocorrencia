<?php
    require_once('../config.php');

    try {
        include_once('../conexao.php');

        /* BUSCANDO OS DADOS DA OCORRENCIA */

        $pyocorrencia = md5('idocorrencia');
        $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial FROM ocorrencia WHERE idocorrencia = :idocorrencia");
        $sql->bindParam(':idocorrencia', $_GET[''.$pyocorrencia.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
                $lin->serial = preg_replace("/[^0-9\s]/", "", $lin->serial);
                
                $descricao = '-';
                $sql2 = $pdo->prepare("SELECT vtotal FROM conta WHERE idocorrencia = :idocorrencia AND descricao <> :descricao ORDER BY descricao,quantidade,vunitario,total");
                $sql2->bindParam(':idocorrencia', $lin->idocorrencia, PDO::PARAM_INT);
                $sql2->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                $sql2->execute();
                $ret2 = $sql2->rowCount();

                    if($ret2 > 0) {
                        $total = 0;

                            while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                $total = $lin2->vtotal + $total;
                            }

                        unset($lin2);
                    }
                
                // DADOS DO BOLETO PARA O SEU CLIENTE
                $dias_de_prazo_para_pagamento = 10;
                $taxa_boleto = 0;
                $data_venc = date("Ymd", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
                $data_emissao = date("Ymd"); 
                $valor_cobrado = $total; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
                $valor_cobrado = str_replace(",", ".",$valor_cobrado);
                
                $sql3 = $pdo->prepare("SELECT nome,cpf_cnpj,cep,endereco,bairro,cidade,estado,telefone,email FROM cliente WHERE nome = :nome");
                $sql3->bindParam(':nome', $lin->cliente, PDO::PARAM_STR);
                $sql3->execute();
                $ret3 = $sql3->rowCount();
                
                    if($ret3 > 0) {
                        $lin3 = $sql3->fetch(PDO::FETCH_OBJ);
                        
                        $ddd = substr($lin3->telefone, 1, 2);
                        $telefone = substr($lin3->telefone, 5);
?>
<html>
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <title>Boleto Sicoob</title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <style>
            body {
                font-family: 'Open Sans', sans-serif;
            }
            
            table {
                width: 100%;
            }
            
            td > div {
                text-align: right;
            }
            
            table .tr-header {
                height: 40px;
                background-color: #339999;
            }
            
            table .tr-header td {
                padding-left: 5px;
                text-transform: uppercase;
                font-weight: 700;
            }
            
            table .td-center {
                text-align: center;
            }
            
            input {
                font-family: 'Open Sans', sans-serif;
                padding: 5px;
                min-width: 200px;
            }
            
            button {
                padding: 15px;
                background-color: #339999;
                text-transform: uppercase;
                font-weight: 700;
                font-size: 1em;
            }
        </style>
    </head>
    <body>
        <!-- As casas decimais devem ser separadas por ponto(.) Ex.: 1556.23 (Um mil e quinhentos e cinquenta e seis e vinte e três) -->
        <!-- Os campos de data devem ser preenchidos no padrão americano(aaaaMMdd) sem caractéres especiais. Ex.: 19840206 equivalente a (06/02/1984) -->

        <form method="post" action="https://geraboleto.sicoobnet.com.br/geradorBoleto/GerarBoleto.do">
            <input name="numCliente" type="hidden" value="2687780" size="15" />
            <input name="coopCartao" type="hidden" value="3069" size="15" />
            <input name="chaveAcessoWeb" type="hidden" value="4B2DA778-F823-43E2-9E03-50E605823793" size="25" />
            <input name="numContaCorrente" type="hidden" value="980544" size="15" />
            <input name="codMunicipio" type="hidden" value="16300" size="15" />
            
            <table>
                <tr class="tr-header">
                    <td colspan="6">Sacado</td>
                </tr>
                <tr>
                    <td><div>Nome</div></td>
                    <td><input size="25" maxlength="50" name="nomeSacado" value="<?php echo $lin3->nome;?>" readonly type="text" /></td>
                </tr>
                <tr>
                    <td><div>CPF/CNPJ</div></td>
                    <td>
                        <input size="10" maxlength="14" name="cpfCGC" value="<?php echo $lin3->cpf_cnpj; ?>" readonly type="text" />
                        <!-- Data de nascimento:<input name="dataNascimento" type="text" size="5" /> -->
                    </td>
                </tr>
                <tr>
                    <td><div>Endere&ccedil;o</div></td>
                    <td>
                        <input size="15" maxlength="20" name="endereco" value="<?php echo $lin3->endereco; ?>" readonly type="text" />
                    </td>
                    <td><div>Bairro</div></td>
                    <td>
                        <input size="10" maxlength="15" name="bairro" value="<?php echo $lin3->bairro; ?>" readonly type="text" />
                    </td>
                </tr>
                <tr>
                    <td><div>Cidade</div></td>
                    <td>
                       <input size="15" maxlength="15" name="cidade" value="<?php echo $lin3->cidade; ?>" readonly type="text" />
                    </td>
                    <td><div>CEP</div></td>
                    <td>
                        <input size="8" maxlength="8" name="cep" value="<?php echo $lin3->cep; ?>" readonly type="text" />
                    </td>
                    <td><div>UF</div></td>
                    <td>
                        <input size="5" maxlength="2" name="uf" value="<?php echo $lin3->estado; ?>" readonly type="text" />
                    </td>
                </tr>
                <tr>
                    <td><div>Telefone</div></td>
                    <td>
                        <input name="telefone" value="<?php echo $telefone; ?>" readonly type="text" size="10" />
                    </td>
                    <td><div>DDD</div></td>
                    <td>
                        <input name="ddd" value="<?php echo $ddd; ?>" readonly type="text" size="5" />
                    </td>
                    <td><div>Ramal</div></td>
                    <td>
                        <input name="ramal" type="hidden" size="5" />
                    </td>
                </tr>
                <tr>
                    <td><!-- Recebe email: --></td>
                    <td><input name="bolRecebeBoletoEletronico" type="hidden" value="1" size="3" /></td>
                </tr>
                <tr>
                    <td><div>Email</div></td>
                    <td><input name="email" value="<?php echo $lin3->email; ?>" readonly type="text" size="25" /></td>
                </tr>
            </table>
            <table>
                <input name="codEspDocumento" type="hidden" value="DM" size="5" />
               
                <tr class="tr-header">
                    <td colspan="2">T&iacute;tulo</td>
                </tr>
                <tr>
                    <td><div>Data Emiss&atilde;o</div></td>
                    <td><input name="dataEmissao" value="<?php echo $data_emissao; ?>" readonly type="text" size="10" /></td>
                </tr>
                <tr>
                    <td><div>Seu n&uacute;mero</div></td>
                    <td><input name="seuNumero" value="<?php echo $lin->serial; ?>" readonly type="text" size="25" /></td>
                </tr>
                <tr>
                    <td><div>Nome Sacador</div></td>
                    <td><input name="nomeSacador" value="Embracore Informatica Ltda" readonly type="text" size="25" /></td>
                </tr>
                <tr>
                    <td><div>CNPJ Sacador</div></td>
                    <td><input name="numCGCCPFSacador" value="07781330000100" readonly type="text" size="25" /></td>
                </tr>
                <tr>
                    <td><!-- Quantidade Monetária --></td>
                    <td><input name="qntMonetaria" type="hidden" size="5" /></td>
                </tr>
                <tr>
                    <td><div>Valor T&iacute;tulo</div></td>
                    <td><input name="valorTitulo" value="<?php echo $valor_cobrado; ?>" readonly type="text" size="5" /></td>
                </tr>
                <tr>
                    <td><!-- Código tipo vencimento--></td>
                    <td><input name="codTipoVencimento" type="hidden" value="1" size="5" /></td>
                </tr>
                <tr>
                    <td><div>Data Vencimento</div></td>
                    <td><input name="dataVencimentoTit" value="<?php echo $data_venc; ?>" readonly type="text" size="5" /></td>
                </tr>
                
                <input name="valorAbatimento" type="hidden" value="0" size="5" /> 
                <input name="valorIOF" type="hidden" value="0" size="5" />
                <input name="bolAceite" type="hidden" value="1" size="5" />
                <input name="percTaxaMulta" type="hidden" value="0" size="5" />
                <input name="percTaxaMora" type="hidden" value="0" size="5" />
                <input name="dataPrimDesconto" type="hidden" size="5" />
                <input name="valorPrimDesconto" type="hidden" value="0" size="5" />
                <input name="dataSegDesconto" type="hidden" size="5" />
                <input name="valorSegDesconto" type="hidden" value="0" size="5" />
            </table>
            <table>
                <tr class="tr-header">
                    <td>Instru&ccedil;&atilde;o</td>
                </tr>
                <tr>
                    <td><input name="descInstrucao1" value="Para agilizar, envie o" readonly type="text" /></td>
                </tr>
                <tr>
                    <td><input name="descInstrucao2" value="comprovante para o email" readonly type="text" /></td>
                </tr>
                <tr>
                    <td><input name="descInstrucao3" value="financeiro@embracore.com.br" readonly type="text" /></td>
                </tr>
                <tr>
                    <td><input name="descInstrucao4" value="(47)3365-4410" readonly type="text" /></td>
                </tr>
                <tr>
                    <td><input name="descInstrucao5" type="hidden" /></td>
                </tr>
                <tr>
                    <td class="td-center">
                        <button type="submit">Gerar Boleto</button>
                        <a class="btn btn-default" title="Fechar ocorr&ecirc;ncia e voltar para o in&iacute;cio" href="../inicio.php">Sair</a>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
<?php           
                    } else {
                        header('location: ../inicio.php');
                    }
                
                unset($lin,$descricao,$total,$sql2,$ret2,$serial);
            }
        
        unset($pdo,$sql,$ret,$pyocorrencia);
    }
    catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }
?>