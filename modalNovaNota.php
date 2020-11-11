<div class="modal fade" id="nova-nota" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-nova-nota">
                <div class="modal-header">
                    <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Nova nota <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
                        <div class="input-group col-md-3">
                            <input type="text" id="datado-nota" class="form-control" maxlength="10" value="<?php echo date('d/m/Y'); ?>" title="Digite a data" placeholder="Data" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tecnico"><i class="fa fa-asterisk"></i> T&eacute;cnico</label>
                        <div class="input-group col-md-6">
                            <select id="tecnico-nota" class="form-control" required>
                                <option value="" selected>Selecione o t&eacute;cnico</option>
                                <?php
                                    try {
                                        include_once('conexao.php');
                                        
                                        $sql = $pdo->prepare("SELECT nome FROM tecnico ORDER BY nome");
                                        $sql->execute();
                                        $ret = $sql->rowCount();

                                            if($ret > 0) {
                                                while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                    echo'<option value="'.$lin->nome.'">'.$lin->nome.'</option>';
                                                }

                                                unset($lin);
                                            }

                                        unset($sql,$ret);
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
                        <textarea id="texto" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary btn-flat btn-submit-nova-nota">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="edita-nota" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>
