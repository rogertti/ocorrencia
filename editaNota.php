<?php
    try {
        include_once('conexao.php');

        /* BUSCA DADOS DA NOTA */

        $py = md5('idnota');
        $sql = $pdo->prepare("SELECT idnota,datado,tecnico,texto FROM nota WHERE idnota = :idnota");
        $sql->bindParam(':idnota', $_GET[''.$py.''], PDO::PARAM_INT);
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
<form class="form-edita-nota">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edita nota <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
    </div>
    <div class="modal-body">
        <input type="hidden" id="idnota" class="form-control" value="<?php echo $lin->idnota; ?>">

        <div class="form-group">
            <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
            <div class="input-group col-md-3">
                <input type="text" id="datado-nota-" class="form-control" maxlength="10" value="<?php echo $lin->datado; ?>" title="Digite a data" placeholder="Data" required>
            </div>
        </div>
        <div class="form-group">
            <label for="tecnico"><i class="fa fa-asterisk"></i> T&eacute;cnico</label>
            <div class="input-group col-md-5">
                <select id="tecnico-nota-" class="form-control" required>
                    <option value="" selected>Selecione o t&eacute;cnico</option>
                    <?php
                        try {
                            $sql2 = $pdo->prepare("SELECT nome FROM tecnico ORDER BY nome");
                            $sql2->execute();
                            $ret2 = $sql2->rowCount();

                                if($ret2 > 0) {
                                    while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                        if($lin->tecnico == $lin2->nome) {
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
                        }
                    ?>
                </select>
                <span class="input-group-addon">
                    <a data-toggle="modal" data-target="#novo-tecnico" title="Adicionar um novo t&eacute;cnico" href="#"><i class="fa fa-plus fa-fw"></i></a>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="texto"><i class="fa fa-asterisk"></i> Texto</label>
            <textarea id="texto-" class="form-control" required><?php echo $lin->texto; ?></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-nota">Salvar</button>
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
