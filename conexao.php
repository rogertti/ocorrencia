<?php
	/*define("DB_TYPE","mysql");
	define("DB_HOST","mysql.embracore.com.br");
	define("DB_USER","embracore");
	define("DB_PASS","0095123");
	define("DB_DATA","embracore");*/
    define("DB_TYPE","mysql");
	define("DB_HOST","localhost");
	define("DB_USER","root");
	define("DB_PASS","EMB@3974;");
	define("DB_DATA","ocorrencia");

	$pdo = new PDO("".DB_TYPE.":host=".DB_HOST.";dbname=".DB_DATA."",DB_USER,DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec("SET NAMES utf8");
?>
