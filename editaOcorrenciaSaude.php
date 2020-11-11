<?php
    try {
        include_once('conexao.php');

        /* BUSCA DADOS DA OCORRENCIA */

        $py = md5('idocorrencia');
        $sql = $pdo->prepare("SELECT idocorrencia,cliente,serial,datado,hora,solicitacao,diagnostico,procedimento,observacao,tecnico,retorno,fechada,desativada,entrega FROM ocorrencia WHERE idocorrencia = :idocorrencia");
        $sql->bindParam(':idocorrencia', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);

                //INVERTENDO A DATA

                $ano = substr($lin->datado,0,4);
                $mes = substr($lin->datado,5,2);
                $dia = substr($lin->datado,8);
                $lin->datado = $dia."/".$mes."/".$ano;

                //BUSCANDO DADOS DO CLIENTE

                $sql2 = $pdo->prepare("SELECT idcliente FROM cliente WHERE nome = :nome");
                $sql2->bindParam(':nome', $lin->cliente, PDO::PARAM_STR);
                $sql2->execute();
                $ret2 = $sql2->rowCount();

                    if($ret2 > 0) {
                        $lin2 = $sql2->fetch(PDO::FETCH_OBJ);
                        $idcliente = $lin2->idcliente;
                    }
                    else {
                        $idcliente = '';
                    }

                unset($sql2,$ret2,$lin2);
?>
<form class="form-edita-ocorrencia">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edita ocorr&ecirc;ncia <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small> <span class="pull-right lead"><strong><?php echo $lin->serial; ?></strong></span></h4>
    </div>
    <div class="modal-body overing">
        <div class="col-md-6">
            <input type="hidden" id="idocorrencia" class="form-control" value="<?php echo $lin->idocorrencia; ?>">
            <input type="hidden" id="serial-" class="form-control" value="<?php echo $lin->serial; ?>">

            <div class="form-group">
                <label for="cliente"><i class="fa fa-asterisk"></i> Cliente <cite class="msg-cliente- label label-danger"></cite></label>
                <input type="hidden" id="idcliente-" class="form-control" value="<?php echo $idcliente; ?>">
                <input type="text" id="cliente-" class="form-control" maxlength="255" value="<?php echo $lin->cliente; ?>" title="Digite o nome do cliente" placeholder="Cliente" required readonly>
            </div>
            <div class="form-group">
                <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
                <div class="input-group col-md-6">
                    <input type="text" id="datado-" class="form-control" maxlength="10" value="<?php echo $lin->datado; ?>" title="Digite a data" placeholder="Data" required>
                </div>
            </div>
            <div class="form-group">
                <label for="hora"><i class="fa fa-asterisk"></i> Hora</label>
                <div class="input-group col-md-6">
                    <select id="hora-" class="form-control">
                    <?php
                         if ($lin->hora == '08:00') { echo'<option value="08:00" selected="selected">08:00</option>'; }
                         else { echo'<option value="08:00">08:00</option>'; }

                         if ($lin->hora == '08:30') { echo'<option value="08:30" selected="selected">08:30</option>'; }
                         else { echo'<option value="08:30">08:30</option>'; }

                         if ($lin->hora == '09:00') { echo'<option value="09:00" selected="selected">09:00</option>'; }
                         else { echo'<option value="09:00">09:00</option>'; }

                         if ($lin->hora == '09:30') { echo'<option value="09:30" selected="selected">09:30</option>'; }
                         else { echo'<option value="09:30">09:30</option>'; }

                         if ($lin->hora == '10:00') { echo'<option value="10:00" selected="selected">10:00</option>'; }
                         else { echo'<option value="10:00">10:00</option>'; }

                         if ($lin->hora == '10:30') { echo'<option value="10:30" selected="selected">10:30</option>'; }
                         else { echo'<option value="10:30">10:30</option>'; }

                         if ($lin->hora == '11:00') { echo'<option value="11:00" selected="selected">11:00</option>'; }
                         else { echo'<option value="11:00">11:00</option>'; }

                         if ($lin->hora == '11:30') { echo'<option value="11:30" selected="selected">11:30</option>'; }
                         else { echo'<option value="11:30">11:30</option>'; }

                         if ($lin->hora == '13:30') { echo'<option value="13:30" selected="selected">13:30</option>'; }
                         else { echo'<option value="13:30">13:30</option>'; }

                         if ($lin->hora == '14:00') { echo'<option value="14:00" selected="selected">14:00</option>'; }
                         else { echo'<option value="14:00">14:00</option>'; }

                         if ($lin->hora == '14:30') { echo'<option value="14:30" selected="selected">14:30</option>'; }
                         else { echo'<option value="14:30">14:30</option>'; }

                         if ($lin->hora == '15:00') { echo'<option value="15:00" selected="selected">15:00</option>'; }
                         else { echo'<option value="15:00">15:00</option>'; }

                         if ($lin->hora == '15:30') { echo'<option value="15:30" selected="selected">15:30</option>'; }
                         else { echo'<option value="15:30">15:30</option>'; }

                         if ($lin->hora == '16:00') { echo'<option value="16:00" selected="selected">16:00</option>'; }
                         else { echo'<option value="16:00">16:00</option>'; }

                         if ($lin->hora == '16:30') { echo'<option value="16:30" selected="selected">16:30</option>'; }
                         else { echo'<option value="16:30">16:30</option>'; }

                         if ($lin->hora == '17:00') { echo'<option value="17:00" selected="selected">17:00</option>'; }
                         else { echo'<option value="17:00">17:00</option>'; }

                         if ($lin->hora == '17:30') { echo'<option value="17:30" selected="selected">17:30</option>'; }
                         else { echo'<option value="17:30">17:30</option>'; }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-icheck">
                <div class="form-group">
                    <label for="entrega"><i class="fa fa-asterisk"></i> Entregar</label>
                    <div class="input-group">
                    <?php
                        if($lin->entrega == "T") {
                            $js = "entrega";
                            echo'
                            <span class="form-icheck"><input type="radio" name="entrega-" id="onentrega-" value="T" checked> Sim</span>
                            <span class="form-icheck"><input type="radio" name="entrega-" id="offentrega-" value="F"> N&atilde;o</span>';
                        }
                        else {
                            echo'
                            <span class="form-icheck"><input type="radio" name="entrega-" id="onentrega-" value="T"> Sim</span>
                            <span class="form-icheck"><input type="radio" name="entrega-" id="offentrega-" value="F" checked> N&atilde;o</span>';
                        }
                    ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fechada"><i class="fa fa-asterisk"></i> Concluir</label>
                    <div class="input-group">
                    <?php
                        if($lin->fechada == "T") {
                            $js = "fechada";
                            echo'
                            <span class="form-icheck"><input type="radio" name="fechada-" id="onfechada-" value="T" checked> Sim</span>
                            <span class="form-icheck"><input type="radio" name="fechada-" id="offfechada-" value="F"> N&atilde;o</span>';
                        }
                        else {
                            echo'
                            <span class="form-icheck"><input type="radio" name="fechada-" id="onfechada-" value="T" > Sim</span>
                            <span class="form-icheck"><input type="radio" name="fechada-" id="offfechada-" value="F" checked> N&atilde;o</span>';
                        }
                    ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="retorno"><i class="fa fa-asterisk"></i> Retorno</label>
                    <div class="input-group">
                    <?php
                        if($lin->retorno == "T") {
                            $js = "retorno";
                            echo'
                            <span class="form-icheck"><input type="radio" name="retorno-" id="onretorno-" value="T" checked> Sim</span>
                            <span class="form-icheck"><input type="radio" name="retorno-" id="offretorno-" value="F"> N&atilde;o</span>';
                        }
                        else {
                            echo'
                            <span class="form-icheck"><input type="radio" name="retorno-" id="onretorno-" value="T"> Sim</span>
                            <span class="form-icheck"><input type="radio" name="retorno-" id="offretorno-" value="F" checked> N&atilde;o</span>';
                        }
                    ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="viacliente"> Via</label>
                    <div class="input-group">
                        <span class="form-icheck"><input type="checkbox" name="viacliente-" id="viacliente-" value="T"> Imprimir uma via para o cliente</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="tecnico"><i class="fa fa-asterisk"></i> T&eacute;cnico</label>
                <div class="input-group col-md-6">
                    <select id="tecnico-" class="form-control" required>
                        <!--<option value="" selected>Selecione o t&eacute;cnico</option>-->
                        <option value="Vagner" selected>Vagner</option>
                        <?php
                            /*try {
                                $sql2 = $pdo->prepare("SELECT nome FROM tecnico ORDER BY nome");
                                $sql2->execute();
                                $ret2 = $sql2->rowCount();

                                    if($ret2 > 0) {
                                        while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                            if($lin2->nome == $lin->tecnico) {
                                                echo'<option value="'.$lin2->nome.'" selected>'.$lin2->nome.'</option>';
                                            }
                                            else {
                                                echo'<option value="'.$lin2->nome.'">'.$lin2->nome.'</option>';
                                            }
                                        }

                                        unset($lin2);
                                    }

                                unset($sql2,$ret2);
                            }
                            catch(PDOException $e) {
                                echo 'Erro ao conectar o servidor '.$e->getMessage();
                            }*/
                        ?>
                    </select>
                    <!--<span class="input-group-addon">
                        <a data-toggle="modal" data-target="#novo-tecnico" title="Adicionar um novo t&eacute;cnico" href="#"><i class="fa fa-plus fa-fw"></i></a>
                    </span>-->
                </div>
            </div>
            <div class="form-group">
                <label for="solicitacao"><i class="fa fa-asterisk"></i> Solicita&ccedil;&atilde;o</label>
                <textarea id="solicitacao-" class="form-control" required><?php echo $lin->solicitacao; ?></textarea>
            </div>
            <div class="form-group">
                <label for="diagnostico">Diagn&oacute;stico</label>
                <textarea id="diagnostico-" class="form-control"><?php echo $lin->diagnostico; ?></textarea>
            </div>
            <div class="form-group">
                <label for="procedimento">Procedimento</label>
                <textarea id="procedimento-" class="form-control"><?php echo $lin->procedimento; ?></textarea>
            </div>
            <div class="form-group">
                <label for="observacao">Observa&ccedil;&atilde;o</label>
                <textarea id="observacao-" class="form-control"><?php echo $lin->observacao; ?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-ocorrencia">Salvar</button>
    </div>
</form>
<script src="js/apart.min.js"></script>
<script>
    (function() {
    <?php
        if(empty($js)) { $js = "aberta"; }

        switch($js) {
        case 'entrega':
            echo'
            $("#onfechada-").iCheck("disable");
            $("#onretorno-").iCheck("disable");';
            break;

        case 'fechada':
            echo'
            $("#onentrega-").iCheck("disable");
            $("#onretorno-").iCheck("disable");';
            break;

        case 'retorno':
            echo'
            $("#onentrega-").iCheck("disable");
            $("#onfechada-").iCheck("disable");';
            break;
        }
    ?>
    })(jQuery);
</script>
<?php
                unset($lin,$ano,$mes,$dia,$js,$idcliente);
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