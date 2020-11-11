<?php
    // +----------------------------------------------------------------------+
    // | BoletoPhp - Vers„o Beta                                              |
    // +----------------------------------------------------------------------+
    // | Este arquivo est· disponÌvel sob a LicenÁa GPL disponÌvel pela Web   |
    // | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
    // | VocÍ deve ter recebido uma cÛpia da GNU Public License junto com     |
    // | esse pacote; se n„o, escreva para:                                   |
    // |                                                                      |
    // | Free Software Foundation, Inc.                                       |
    // | 59 Temple Place - Suite 330                                          |
    // | Boston, MA 02111-1307, USA.                                          |
    // +----------------------------------------------------------------------+

    // +----------------------------------------------------------------------+
    // | Originado do Projeto BBBoletoFree que tiveram colaboraÁıes de Daniel |
    // | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
    // | PHPBoleto de Jo„o Prado Maia e Pablo Martins F. Costa				        |
    // | 														                                   			  |
    // | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
    // | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
    // +----------------------------------------------------------------------+

    // +----------------------------------------------------------------------+
    // | Equipe CoordenaÁ„o Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
    // | Desenvolvimento Boleto Ita˙: Glauber Portella                        |
    // +----------------------------------------------------------------------+


    // ------------------------- DADOS DIN¬MICOS DO SEU CLIENTE PARA A GERA«√O DO BOLETO (FIXO OU VIA GET) -------------------- //
    // Os valores abaixo podem ser colocados manualmente ou ajustados p/ formul·rio c/ POST, GET ou de BD (MySql,Postgre,etc)	//

    session_start();

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
                $dias_de_prazo_para_pagamento = 30;
                $taxa_boleto = 0;
                $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
                $valor_cobrado = $total; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
                $valor_cobrado = str_replace(",", ".", $valor_cobrado);
                $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');

                // REMOVENDO LETRAS E SIMBOLOS PARA GERAR NOSSO NUMERO
                $serial = $lin->serial;
                $lin->serial = preg_replace("/[^0-9\s]/", "", $lin->serial);

                $dadosboleto["nosso_numero"] = $lin->serial;  // Nosso numero - REGRA: M·ximo de 8 caracteres!
                $dadosboleto["numero_documento"] = $serial;	// Num do pedido ou nosso numero
                $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
                $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss„o do Boleto
                $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
                $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vÌrgula e sempre com duas casas depois da virgula

                // DADOS DO SEU CLIENTE
                $dadosboleto["sacado"] = $lin->cliente;
                $dadosboleto["endereco1"] = "";
                $dadosboleto["endereco2"] = "";

                // INFORMACOES PARA O CLIENTE
                $dadosboleto["demonstrativo1"] = "Cobran&ccedil;a referente a ocorr&ecirc;ncia ".$serial;
                $dadosboleto["demonstrativo2"] = "";
                /*$dadosboleto["demonstrativo2"] = "Taxa banc·ria - R$ ".number_format($taxa_boleto, 2, ',', '');*/
                $dadosboleto["demonstrativo3"] = "";
                $dadosboleto["instrucoes1"] = "- Cobrar multa de 2% ap&oacute;s o vencimento";
                $dadosboleto["instrucoes2"] = "";
                $dadosboleto["instrucoes3"] = "";
                $dadosboleto["instrucoes4"] = "";

                // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
                $dadosboleto["quantidade"] = "";
                $dadosboleto["valor_unitario"] = "";
                $dadosboleto["aceite"] = "";
                $dadosboleto["especie"] = "R$";
                $dadosboleto["especie_doc"] = "DM";


                // ---------------------- DADOS FIXOS DE CONFIGURA«√O DO SEU BOLETO --------------- //


                // DADOS DA SUA CONTA - ITA⁄
                $dadosboleto["agencia"] = "7293"; // Num da agencia, sem digito
                $dadosboleto["conta"] = "14044";	// Num da conta, sem digito
                $dadosboleto["conta_dv"] = "3"; 	// Digito do Num da conta

                // DADOS PERSONALIZADOS - ITA⁄
                $dadosboleto["carteira"] = "157";  // CÛdigo da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

                // SEUS DADOS
                $dadosboleto["identificacao"] = "Embracore, Ltda.";
                $dadosboleto["cpf_cnpj"] = "07.781.330/0001-00";
                $dadosboleto["endereco"] = "Rua Jos&eacute; Francisco Bernardes, 733";
                $dadosboleto["cidade_uf"] = "Centro - Cambori&uacute; - SC";
                $dadosboleto["cedente"] = "Embracore Inform&aacute;tica Ltda ME";

                // N√O ALTERAR!
                include("funcoesItau.php");
                include("layoutItau.php");

                unset($lin,$descricao,$total,$sql2,$ret2,$serial);
            }

        unset($pdo,$sql,$ret,$pyocorrencia);
    }
    catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }
?>
