<?php

return createDb();

//Salvando os dados em SQLite
function createDb(){

	$pdo = Connect::hdl();

	//Criando o banco de dados
	$pdo->exec('CREATE DATABASE IF NOT EXISTS megasena 
				DEFAULT CHARACTER SET utf8 
				COLLATE utf8_general_ci');
	
	$pdo->exec('CREATE TABLE IF NOT EXISTS megasena.resultados (
				  ID int(10) unsigned NOT NULL COMMENT \'NUMERO DO CONCURSO\',
				  ACERTO int(10) unsigned NOT NULL COMMENT \'ACERTADORES\',
				  D1 int(10) unsigned NOT NULL,
				  D2 int(10) unsigned NOT NULL,
				  D3 int(10) unsigned NOT NULL,
				  D4 int(10) unsigned NOT NULL,
				  D5 int(10) unsigned NOT NULL,
				  D6 int(10) unsigned NOT NULL,
				  PRIMARY KEY (ID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT=\'Download do site da www.caixa.gov.br\'');
}