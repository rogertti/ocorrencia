<?php
    try {
        include_once('conexao.php');

        /* BUSCA DADOS DA NOTA */

        $py = md5('idtecnico');
        $sql = $pdo->prepare("SELECT idtecnico,nome FROM tecnico WHERE idtecnico = :idtecnico");
        $sql->bindParam(':idtecnico', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edita-tecnico">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edita t&eacute;cnico <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
    </div>
    <div class="modal-body">
        <input type="hidden" id="idtecnico" class="form-control" value="<?php echo $lin->idtecnico; ?>">

        <div class="form-group">
            <label for="nome"><i class="fa fa-asterisk"></i> Nome</label>
            <input type="text" id="nome-" class="form-control" maxlength="255" value="<?php echo $lin->nome; ?>" title="Digite o nome do t&eacute;cnico" placeholder="Nome" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-tecnico">Salvar</button>
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
