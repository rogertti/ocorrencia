<?php
    #header("Content-Type: text/html; charset=utf-8",true);

    $q = strtolower($_GET['q']);
	if (!$q) return;

	$db = dbase_open('estoque.dbf', 0);

        if ($db) {
            $record_numbers = dbase_numrecords($db);
            $items = array();

                for ($i = 1;$i <= $record_numbers;$i++) {
                    $row = dbase_get_record($db, $i);
                    $row[2] = trim($row[2]);
                    $row[2] = utf8_encode($row[2]);
                    $row[2] = ucfirst(strtolower($row[2]));
                    $items[$row[2]] = ('');
                }
        }

    dbase_close($db);

        foreach ($items as $key=>$value) {
            if (strpos(strtolower($key), $q) !== false) {
                echo "$key|$value\n";
            }
        }

    unset($q,$db,$record_numbers,$items,$i,$row,$key,$value);
?>
