<?php
    $q = strtolower($_GET['q']);
	if (!$q) return;

    include_once('conexao.php');

    $sql = $pdo->prepare("SELECT idcliente,nome FROM cliente ORDER BY nome");
    $sql->execute();
    $ret = $sql->rowCount();

        if($ret > 0) {
            $items = array();

                while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                    //$items[$lin->nome] = ('');
                    $items[$lin->nome] = ($lin->idcliente);
                }

                foreach($items as $key=>$value) {
                    if(strpos(strtolower($key), $q) !== false) {
                        echo "$key|$value\n";
                    }
                }

            unset($lin,$items,$key,$value);
        }

    unset($pdo,$sql,$ret,$q);
	/*$db = dbase_open('clientes.dbf', 0);

        if ($db) {
            $record_numbers = dbase_numrecords($db);
            $items = array();

                for ($i = 1;$i <= $record_numbers;$i++) {
                    $row = dbase_get_record($db, $i);
                    $row[0] = trim($row[0]);
                    $row[0] = utf8_encode($row[0]);
                    $items[$row[0]] = ('');
                }
        }

    dbase_close($db);

        foreach ($items as $key=>$value) {
            if (strpos(strtolower($key), $q) !== false) {
                echo "$key|$value\n";
            }
        }

    unset($q,$db,$record_numbers,$items,$i,$row,$key,$value);*/
?>
