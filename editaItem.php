<?php
    try {
        include_once('conexao.php');

        /* BUSCA ITENS DA OCORRENCIA */

        $pyconta = md5('idconta');
        $pyocorrencia = md5('idocorrencia');
        $pyserial = md5('idserial');
        $sql = $pdo->prepare("SELECT idconta,idocorrencia,descricao,quantidade,vunitario,vtotal,total,desconto FROM conta WHERE idconta = :idconta");
        $sql->bindParam(':idconta', $_GET[''.$pyconta.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edita-item">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edita item <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
    </div>
    <div class="modal-body">
        <input type="hidden" id="idconta" class="form-control" value="<?php echo $lin->idconta; ?>">
        <input type="hidden" id="idocorrencia" class="form-control" value="<?php echo $_GET[''.$pyocorrencia.'']; ?>">
        <input type="hidden" id="serial" class="form-control" value="<?php echo $_GET[''.$pyserial.'']; ?>">

        <div class="form-group">
            <label for="descricao"><i class="fa fa-asterisk"></i> Descri&ccedil;&atilde;o</label>
            <input type="text" id="descricao-" class="form-control" maxlength="255" value="<?php echo $lin->descricao; ?>" title="Digite a descri&ccedil;&atilde;o do item" placeholder="Descri&ccedil;&atilde;o" required>
        </div>
        <div class="form-group">
            <label for="valor"><i class="fa fa-asterisk"></i> Valor (R$)</label>
            <div class="input-group col-md-4">
                <input type="text" id="valor-" class="form-control" maxlength="10" value="<?php echo $lin->vunitario; ?>" title="Digite a descri&ccedil;&atilde;o do item" placeholder="Valor" required>
            </div>
        </div>
        <div class="form-group">
            <label for="quantidade"><i class="fa fa-asterisk"></i> Quantidade</label>
            <div class="input-group col-md-4">
                <input type="text" id="quantidade-" class="form-control" maxlength="3" value="<?php echo $lin->quantidade; ?>" title="Digite a quantidade do item" placeholder="Quantidade" required>
            </div>
        </div>
        <div class="form-group">
            <label for="desconto">Desconto (R$)</label>
            <div class="input-group col-md-4">
                <input type="text" id="desconto-" class="form-control" maxlength="10" value="<?php echo $lin->desconto; ?>" title="Desconto do item" placeholder="Desconto">
            </div>
        </div>
        <div class="form-group">
            <label for="subtotal"><i class="fa fa-asterisk"></i> Subtotal (R$)</label>
            <div class="input-group col-md-4">
                <input type="text" id="subtotal-" class="form-control" maxlength="10" value="<?php echo $lin->vtotal; ?>" title="Subtotal do item" placeholder="Subtotal" disabled>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-item">Salvar</button>
    </div>
</form>
<script src="js/apart.min.js"></script>
<?php
                unset($lin);
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
