<div class="modal fade" id="nova-ocorrencia" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-nova-ocorrencia">
            <?php
                //GERANDO O SERIAL

                $rnd = substr(md5(rand()),0,2);
                $rnd2 = substr(md5(rand()),2,2);
                $serial = md5(rand());
                $serial = base64_encode($serial);
                $serial = substr($serial,0,2);
                $rnd = strtoupper($rnd);
                $rnd2 = strtoupper($rnd2);
                $serial = strtoupper($serial);
                $serial = $serial.date('dm').$rnd2.$rnd;
            ?>
                <div class="modal-header">
                    <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Nova ocorr&ecirc;ncia <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small> <span class="pull-right lead"><strong><?php echo $serial; ?></strong></span></h4>
                </div>
                <div class="modal-body overing">
                    <div class="col-md-6">
                        <input type="hidden" id="serial" class="form-control" value="<?php echo $serial; ?>">
                        <input type="hidden" id="stamp" class="form-control" value="<?php echo time()/* + (7 * 24 * 60 * 60)*/; ?>">

                        <div class="form-group">
                            <label for="cliente"><i class="fa fa-asterisk"></i> Cliente <cite class="msg-cliente label label-danger"></cite></label>
                            <input type="hidden" id="idcliente" class="form-control">
                            <input type="text" id="cliente" class="form-control" maxlength="255" title="Digite o nome do cliente" placeholder="Cliente" required>
                            <!--<select id="cliente" class="form-control" title="Digite o nome do cliente" required>
                                <option value="" selected></option>
                                <?php
                                    /*$dba = dbase_open('clientes.dbf', 0);

                                        if ($dba) {
                                            $rec = dbase_numrecords($dba);

                                                for ($i = 1;$i <= $rec;$i++) {
                                                    $row = dbase_get_record($dba,$i);
                                                    $row[0] = trim($row[0]);
                                                    $row[0] = utf8_encode($row[0]);
                                                    echo'<option value="'.$row[0].'">'.$row[0].'</option>';
                                                }
                                        }

                                    dbase_close($dba);
                                    unset($dba,$rec,$i,$row);*/
                                ?>
                            </select>-->
                        </div>
                        <div class="form-group">
                            <label for="datado"><i class="fa fa-asterisk"></i> Data</label>
                            <div class="input-group col-md-6">
                                <input type="text" id="datado" class="form-control" maxlength="10" value="<?php echo date('d/m/Y'); ?>" title="Digite a data" placeholder="Data" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hora"><i class="fa fa-asterisk"></i> Hora</label>
                            <div class="input-group col-md-6">
                                <select id="hora" class="form-control" required>
                                    <option value="" selected>Selecione a hora</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-icheck">
                            <div class="form-group">
                                <label for="entrega"><i class="fa fa-asterisk"></i> Entregar</label>
                                <div class="input-group">
                                    <span class="form-icheck"><input type="radio" name="entrega" id="onentrega" value="T"> Sim</span>
                                    <span class="form-icheck"><input type="radio" name="entrega" id="offentrega" value="F" checked> N&atilde;o</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fechada"><i class="fa fa-asterisk"></i> Concluir</label>
                                <div class="input-group">
                                    <span class="form-icheck"><input type="radio" name="fechada" id="onfechada" value="T"> Sim</span>
                                    <span class="form-icheck"><input type="radio" name="fechada" id="offfechada" value="F" checked> N&atilde;o</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="retorno"><i class="fa fa-asterisk"></i> Retorno</label>
                                <div class="input-group">
                                    <span class="form-icheck"><input type="radio" name="retorno" id="onretorno" value="T"> Sim</span>
                                    <span class="form-icheck"><input type="radio" name="retorno" id="offretorno" value="F" checked> N&atilde;o</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="viacliente"> Via</label>
                                <div class="input-group">
                                    <span class="form-icheck"><input type="checkbox" name="viacliente" id="viacliente" value="T"> Imprimir uma via para o cliente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tecnico"><i class="fa fa-asterisk"></i> T&eacute;cnico</label>
                            <div class="input-group col-md-6">
                                <select id="tecnico" class="form-control" required>
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
                            <label for="solicitacao"><i class="fa fa-asterisk"></i> Solicita&ccedil;&atilde;o</label>
                            <textarea id="solicitacao" class="form-control" title="Digite a solicita&ccedil;&atilde;o" placeholder="Solicita&ccedil;&atilde;o" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="diagnostico">Diagn&oacute;stico</label>
                            <textarea id="diagnostico" class="form-control" title="Digite o diagn&oacute;stico" placeholder="Diagn&oacute;stico"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="procedimento">Procedimento</label>
                            <textarea id="procedimento" class="form-control" title="Digite o procedimento" placeholder="Procedimento"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="observacao">Observa&ccedil;&atilde;o</label>
                            <textarea id="observacao" class="form-control" title="Digite a observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary btn-flat btn-submit-nova-ocorrencia">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="edita-ocorrencia" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
