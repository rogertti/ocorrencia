<?php
    set_time_limit(0);

    $stamp = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : time();

    include_once('conexao.php');

        while(true) {
            $sql = $pdo->prepare("SELECT stamp FROM ocorrencia WHERE stamp > :stamp");
            $sql->bindParam(':stamp', $stamp, PDO::PARAM_STR);
            $sql->execute();
            $lin = $sql->fetchAll(PDO::FETCH_ASSOC);

                if(count($lin) > 0) {
                    $json = json_encode($lin);
                    echo $json;
                    break;
                }
                else {
                    sleep(2);
                    continue;
                }

            unset($sql,$lin,$json);
        }

    unset($pdo,$stamp);
?>
