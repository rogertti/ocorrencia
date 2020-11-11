<?php
    try {
        include_once('conexao.php');

        /* BUSCA DADOS DO CLIENTE */

        $py = md5('idcliente');
        $sql = $pdo->prepare("SELECT idcliente,nome,cpf_cnpj,rg_ie,cep,endereco,bairro,cidade,estado,telefone,celular,email,nascimento,observacao FROM cliente WHERE idcliente = :idcliente");
        $sql->bindParam(':idcliente', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);

                //INVERTENDO A DATA

                $ano = substr($lin->nascimento,0,4);
                $mes = substr($lin->nascimento,5,2);
                $dia = substr($lin->nascimento,8);
                $lin->nascimento = $dia."/".$mes."/".$ano;
?>
<form class="form-edita-cliente">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edita cliente <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
    </div>
    <div class="modal-body overing">
        <div class="col-md-6">
            <input type="hidden" id="idcliente-" class="form-control" value="<?php echo $lin->idcliente; ?>">
            <?php
                $documento = strlen($lin->cpf_cnpj);

                    switch($documento) {
                        case 14:
            ?>
            <div class="form-group">
                <label for="pessoa"><i class="fa fa-asterisk"></i> Pessoa</label>
                <div class="input-group">
                    <span class="form-icheck"><input type="radio" name="pessoa" id="fisica-" value="F" checked> F&iacute;sica</span>
                    <span class="form-icheck"><input type="radio" name="pessoa" id="juridica-" value="J"> Jur&iacute;dica</span>
                </div>
            </div>
            <div class="form-group">
                <label class="label-nome-" for="nome-cliente"><i class="fa fa-asterisk"></i> Nome</label>
                <input type="text" id="nome-cliente-" class="form-control" value="<?php echo $lin->nome; ?>" maxlength="255" title="Digite o nome do cliente" placeholder="Nome" required>
            </div>
            <div class="form-group">
                <label class="label-nascimento-" for="nascimento">Nascimento <cite class="msg-nascimento- label label-danger"></cite></label>
                <div class="input-group col-md-4">
                    <input type="text" id="nascimento-" class="form-control" value="<?php echo $lin->nascimento; ?>" maxlength="10" title="Digite a data de nascimento" placeholder="Nascimento">
                </div>
            </div>
            <div class="form-group">
                <label class="label-documento-" for="cpf"><i class="fa fa-asterisk"></i> CPF <cite class="msg-documento- label label-danger"></cite></label>
                <div class="input-group col-md-6">
                    <input type="text" id="cpf-" class="form-control" value="<?php echo $lin->cpf_cnpj; ?>" maxlength="14" title="Digite o CPF" placeholder="CPF" required>
                    <input type="text" id="cnpj-" class="form-control hide" maxlength="18" title="Digite o CNPJ" placeholder="CNPJ">
                    <span class="help-block msg-cpf-"></span>
                    <span class="help-block msg-cnpj- hide"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="label-documento-2-" for="rg">RG</label>
                <div class="input-group col-md-4">
                    <input type="text" id="rg-" class="form-control" value="<?php echo $lin->rg_ie; ?>" maxlength="20" title="Digite o RG" placeholder="RG">
                    <input type="text" id="ie-" class="form-control hide" maxlength="20" title="Digite a IE" placeholder="IE">
                </div>
            </div>
            <?php
                        break;
                        case 18:
            ?>
            <div class="form-group">
                <label for="pessoa"><i class="fa fa-asterisk"></i> Pessoa</label>
                <div class="input-group">
                    <span class="form-icheck"><input type="radio" name="pessoa" id="fisica-" value="F"> F&iacute;sica</span>
                    <span class="form-icheck"><input type="radio" name="pessoa" id="juridica-" value="J" checked> Jur&iacute;dica</span>
                </div>
            </div>
            <div class="form-group">
                <label class="label-nome-" for="nome-cliente"><i class="fa fa-asterisk"></i> Raz&atilde;o Social</label>
                <input type="text" id="nome-cliente-" class="form-control" value="<?php echo $lin->nome; ?>" maxlength="255" title="Digite o nome do cliente" placeholder="Nome" required>
            </div>
            <div class="form-group">
                <label class="label-nascimento-" for="nascimento">Funda&ccedil;&atilde;o <cite class="msg-nascimento- label label-danger"></cite></label>
                <div class="input-group col-md-4">
                    <input type="text" id="nascimento-" class="form-control" value="<?php echo $lin->nascimento; ?>" maxlength="10" title="Digite a data de nascimento" placeholder="Funda&ccedil;&atilde;o">
                </div>
            </div>
            <div class="form-group">
                <label class="label-documento-" for="cpf"><i class="fa fa-asterisk"></i> CNPJ <cite class="msg-documento- label label-danger"></cite></label>
                <div class="input-group col-md-6">
                    <input type="text" id="cpf-" class="form-control hide" maxlength="14" title="Digite o CPF" placeholder="CPF">
                    <input type="text" id="cnpj-" class="form-control" value="<?php echo $lin->cpf_cnpj; ?>" maxlength="18" title="Digite o CNPJ" placeholder="CNPJ" required>
                    <span class="help-block msg-cpf- hide"></span>
                    <span class="help-block msg-cnpj-"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="label-documento-2-" for="rg">IE</label>
                <div class="input-group col-md-4">
                    <input type="text" id="rg-" class="form-control hide" maxlength="20" title="Digite o RG" placeholder="RG">
                    <input type="text" id="ie-" class="form-control" value="<?php echo $lin->rg_ie; ?>" maxlength="20" title="Digite a IE" placeholder="IE">
                </div>
            </div>
            <?php
                        break;
                        default:
            ?>
            <div class="form-group">
                <label for="pessoa"><i class="fa fa-asterisk"></i> Pessoa</label>
                <div class="input-group">
                    <span class="form-icheck"><input type="radio" name="pessoa" id="fisica-" value="F" checked> F&iacute;sica</span>
                    <span class="form-icheck"><input type="radio" name="pessoa" id="juridica-" value="J"> Jur&iacute;dica</span>
                </div>
            </div>
            <div class="form-group">
                <label class="label-nome-" for="nome-cliente"><i class="fa fa-asterisk"></i> Nome</label>
                <input type="text" id="nome-cliente-" class="form-control" value="<?php echo $lin->nome; ?>" maxlength="255" title="Digite o nome do cliente" placeholder="Nome" required>
            </div>
            <div class="form-group">
                <label class="label-nascimento-" for="nascimento">Nascimento <cite class="msg-nascimento- label label-danger"></cite></label>
                <div class="input-group col-md-4">
                    <input type="text" id="nascimento-" class="form-control" value="<?php echo $lin->nascimento; ?>" maxlength="10" title="Digite a data de nascimento" placeholder="Nascimento">
                </div>
            </div>
            <div class="form-group">
                <label class="label-documento-" for="cpf"><i class="fa fa-asterisk"></i> CPF <cite class="msg-documento- label label-danger"></cite></label>
                <div class="input-group col-md-6">
                    <input type="text" id="cpf-" class="form-control" maxlength="14" title="Digite o CPF" placeholder="CPF" required>
                    <input type="text" id="cnpj-" class="form-control hide" maxlength="18" title="Digite o CNPJ" placeholder="CNPJ">
                    <span class="help-block msg-cpf-"></span>
                    <span class="help-block msg-cnpj- hide"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="label-documento-2-" for="rg">RG</label>
                <div class="input-group col-md-4">
                    <input type="text" id="rg-" class="form-control" maxlength="20" title="Digite o RG" placeholder="RG">
                    <input type="text" id="ie-" class="form-control hide" maxlength="20" title="Digite a IE" placeholder="IE">
                </div>
            </div>
            <?php
                        break;
                    } //switch
            ?>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <div class="input-group col-md-4">
                    <input type="text" id="telefone-" class="form-control" value="<?php echo $lin->telefone; ?>" maxlength="13" title="Digite o telefone" placeholder="Telefone">
                </div>
            </div>
            <div class="form-group">
                <label for="celular">Celular</label>
                <div class="input-group col-md-4">
                    <input type="text" id="celular-" class="form-control" value="<?php echo $lin->celular; ?>" maxlength="13" title="Digite o celular" placeholder="Celular">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email-" class="form-control" value="<?php echo $lin->email; ?>" maxlength="100" title="Digite o email" placeholder="Email">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="cep">CEP <cite class="msg-cep- label label-danger"></cite></label>
                <div class="input-group col-md-4">
                    <input type="text" id="cep-" class="form-control" value="<?php echo $lin->cep; ?>" maxlength="9" title="Digite o CEP" placeholder="CEP">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="endereco">Endere&ccedil;o</label>
                <input type="text" id="endereco-" class="form-control" value="<?php echo $lin->endereco; ?>" maxlength="255" title="Digite o endere&ccedil;o" placeholder="Endere&ccedil;o, n&uacute;mero">
            </div>
            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" id="bairro-" class="form-control" value="<?php echo $lin->bairro; ?>" maxlength="100" title="Digite o bairro" placeholder="Bairro">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" id="cidade-" class="form-control" value="<?php echo $lin->cidade; ?>" maxlength="100" title="Digite a cidade" placeholder="Cidade">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <div class="input-group col-md-4">
                    <select id="estado-" class="form-control">
                    <?php
                          if($lin->estado == 'AC') {echo'<option value="AC" selected="selected">AC</option>';} else {echo'<option value="AC">AC</option>';}
                          if($lin->estado == 'AL') {echo'<option value="AL" selected="selected">AL</option>';} else {echo'<option value="AL">AL</option>';}
                          if($lin->estado == 'AM') {echo'<option value="AM" selected="selected">AM</option>';} else {echo'<option value="AM">AM</option>';}
                          if($lin->estado == 'AP') {echo'<option value="AP" selected="selected">AP</option>';} else {echo'<option value="AP">AP</option>';}
                          if($lin->estado == 'BA') {echo'<option value="BA" selected="selected">BA</option>';} else {echo'<option value="BA">BA</option>';}
                          if($lin->estado == 'CE') {echo'<option value="CE" selected="selected">CE</option>';} else {echo'<option value="CE">CE</option>';}
                          if($lin->estado == 'DF') {echo'<option value="DF" selected="selected">DF</option>';} else {echo'<option value="DF">DF</option>';}
                          if($lin->estado == 'ES') {echo'<option value="ES" selected="selected">ES</option>';} else {echo'<option value="ES">ES</option>';}
                          if($lin->estado == 'GO') {echo'<option value="GO" selected="selected">GO</option>';} else {echo'<option value="GO">GO</option>';}
                          if($lin->estado == 'MA') {echo'<option value="MA" selected="selected">MA</option>';} else {echo'<option value="MA">MA</option>';}
                          if($lin->estado == 'MG') {echo'<option value="MG" selected="selected">MG</option>';} else {echo'<option value="MG">MG</option>';}
                          if($lin->estado == 'MS') {echo'<option value="MS" selected="selected">MS</option>';} else {echo'<option value="MS">MS</option>';}
                          if($lin->estado == 'MT') {echo'<option value="MT" selected="selected">MT</option>';} else {echo'<option value="MT">MT</option>';}
                          if($lin->estado == 'PA') {echo'<option value="PA" selected="selected">PA</option>';} else {echo'<option value="PA">PA</option>';}
                          if($lin->estado == 'PB') {echo'<option value="PB" selected="selected">PB</option>';} else {echo'<option value="PB">PB</option>';}
                          if($lin->estado == 'PE') {echo'<option value="PE" selected="selected">PE</option>';} else {echo'<option value="PE">PE</option>';}
                          if($lin->estado == 'PI') {echo'<option value="PI" selected="selected">PI</option>';} else {echo'<option value="PI">PI</option>';}
                          if($lin->estado == 'PR') {echo'<option value="PR" selected="selected">PR</option>';} else {echo'<option value="PR">PR</option>';}
                          if($lin->estado == 'RJ') {echo'<option value="RJ" selected="selected">RJ</option>';} else {echo'<option value="RJ">RJ</option>';}
                          if($lin->estado == 'RN') {echo'<option value="RN" selected="selected">RN</option>';} else {echo'<option value="RN">RN</option>';}
                          if($lin->estado == 'RO') {echo'<option value="RO" selected="selected">RO</option>';} else {echo'<option value="RO">RO</option>';}
                          if($lin->estado == 'RR') {echo'<option value="RR" selected="selected">RR</option>';} else {echo'<option value="RR">RR</option>';}
                          if($lin->estado == 'RS') {echo'<option value="RS" selected="selected">RS</option>';} else {echo'<option value="RS">RS</option>';}
                          if($lin->estado == 'SC') {echo'<option value="SC" selected="selected">SC</option>';} else {echo'<option value="SC">SC</option>';}
                          if($lin->estado == 'SE') {echo'<option value="SE" selected="selected">SE</option>';} else {echo'<option value="SE">SE</option>';}
                          if($lin->estado == 'SP') {echo'<option value="SP" selected="selected">SP</option>';} else {echo'<option value="SP">SP</option>';}
                          if($lin->estado == 'TO') {echo'<option value="TO" selected="selected">TO</option>';} else {echo'<option value="TO">TO</option>';}
                    ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="observacao">Observa&ccedil;&atilde;o</label>
                <textarea id="observacao-cliente-" class="form-control" title="Digite a observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"><?php echo $lin->observacao; ?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-cliente">Salvar</button>
    </div>
</form>
<script src="js/apart.min.js"></script>
<?php
                unset($lin,$ano,$mes,$dia);
            } //if($ret > 0)
            else {
                echo'
                <div class="callout">
                    <h4>Par&acirc;mentro incorreto</h4>
                </div>';
            }

        unset($pdo,$sql,$ret,$py);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>
