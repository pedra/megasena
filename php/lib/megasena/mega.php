<?php
//namespace Megasena;

class Mega {
	
	//Generics parameters
	private $datalink = 'http://www1.caixa.gov.br/loterias/_arquivos/loterias/D_megase.zip';
	private $tmpdir = __DIR__;
	private $error = false;

	//Database parameters
	private $conn = null;
	private $dbtype = 'sqlite';
	
	private $host = 'localhost';
	private $database = 'megasena';	
	private $user = 'megasena';
	private $password = 'mega!1@2#3';

	
	function __construct($update = false){
		//auto update ??
		if($update !== false) {
			if(is_string($update)) $this->datalink = $update;
			$this->update();
		}
	}

	//get/set parameters
	function __call($nm, $arg){
		$nm = strtolower($nm);
		$arg = isset($arg[0]) ? $arg[0] : null;
		$func = substr($nm, 0, 3);
		$par = substr($nm, 3);

		//DEBUG only
		if($func == 'get' && $par == 'printall') return '<pre>'.print_r($this, true).'</pre>';

		//parameter exist?
		if(isset($this->$par)){
			if($func == 'set') return $this->$par = $arg;
			if($func == 'get') return $this->$par; 
		}
		$this->error = 'Parameter does not exist';
		return false;
	}
        
        //Download url
        function download($url, $local){
            passthru('wget "'.$url.'" -O '.$local, $err);
            if (0 == filesize($local)) {
                $this->error = 'Unable to download the file ('.$err.')';
                return false;
            }
            return true;
        }

	// update Megasena database
	function update(){
		$megafile = $this->tmpdir.'/mega.zip';
		
                //kill old file
		$this->kill($megafile);

		//download
                if(!$this->download($this->datalink, $megafile)) return false;

		//Extract datafile
		$zip = new ZipArchive; 
		$zip->open($megafile);
		$zip->extractTo($this->tmpdir);
		$zip->close();
		$this->kill($megafile);
		$this->kill($this->tmpdir.'/T2.GIF');
		if(!file_exists($this->tmpdir.'/D_MEGA.HTM')) {
			$this->error = 'Unable to extract the HTM file';
			return false;
		}

		//Formate file
		$file = file_get_contents($this->tmpdir.'/D_MEGA.HTM');
		$outFile = '';
		$pointer = $i = 0;
		$fileSize = strlen($file);
		$data = array();

		//get HTML to data
		while($i <= $fileSize){
			$i = strpos($file, '<tr', $pointer);
			if($i !== false) {
				$in = strpos($file, '</tr', $i);
				if($in === false) {
					$this->error = 'HTM format error';
					return false;
				}
				$tmp = $this->getTd(substr($file, $i, ($in - $i + 5)));
				//check for error
				if(!is_array($tmp)) return false;
				$data[] = $tmp;
				$pointer = $in;
			} else break;
		}

		$this->kill($this->tmpdir.'/D_MEGA.HTM');
		unset($data[0]);
		
		//insert database
		$this->dbInsert($data);
		return true;
	}

	//Database conector
	final private function db(){
		if ($this->conn == null) {
			if($this->dbtype == 'sqlite') return $this->conn = new PDO('sqlite:'.$this->database);
			if($this->dbtype == 'mysql')  return $this->conn = new PDO('mysql:dbname='.$this->database.';host='.$this->host.';charset=UTF8', $this->user, $this->password);

			//if not conected
			if($this->conn == null) trigger_error($this->error = 'I can not connect to the database');
		}
		return $this->conn;
	}

	//Creating DataBase
	function createDb(){

		$pdo = $this->db();

		//Criando o banco de dados
		$pdo->exec('CREATE DATABASE IF NOT EXISTS megasena 
					DEFAULT CHARACTER SET utf8 
					COLLATE utf8_general_ci');
		
		$pdo->exec('CREATE TABLE IF NOT EXISTS resultados (
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

	//Creating Sqlite Database
	function createSqlite(){
		$pdo = $this->db();
	}
        
        //Get result
        function result($id = null){
            if($id == null) $id = $this->lastResult();                
            foreach($this->db()->query('SELECT * FROM resultados WHERE ID = '.$id.' LIMIT 1') as $row){return $row;}
        }
        
        //Get last result
        function lastResult(){
            foreach($this->db()->query('SELECT * FROM  resultados ORDER BY ID DESC LIMIT 1') as $row){return $row['ID'];}
        }

	//Test get database results
	function getCont(){
		$pdo = $this->db();
                
                //total results
                $total = $this->lastResult();
                
                //get results
		$all = $pdo->query('SELECT * FROM resultados');
		$all = $all->fetchAll();
		
                //create array
                for($i = 1; $i <= 60; $i++){$a[$i] = 0;}
                
		foreach($all as $v){
			$a[$v['D1']] += 1;
                        $a[$v['D2']] += 1;
                        $a[$v['D3']] += 1;
                        $a[$v['D4']] += 1;
                        $a[$v['D5']] += 1;
                        $a[$v['D6']] += 1;
		}
                arsort($a);

		$o = '
		<style>
		table {float:left; border:1px solid #DDD; padding:0; margin:-10px 1px 30px 1px; background:#FFF}
		table tr th {padding:6px 10px; color:#000; border-bottom:1px solid #000; font-size:11px; font-weight:normal}
		table tr td {padding:6px 10px; color:#BBD; border: none; border-bottom:1px solid #DDD}
		table tr:hover td { background:#DDD; color:#000;}
                p {clear:both; margin:20px 0; font-size:10px; padding:0 0 30px 0}
                </style>		
                <h3>Último concurso: '.$total.'<br>Dezenas: '.$v['D1'].'-'.$v['D2'].'-'.$v['D3'].'-'.$v['D4'].'-'.$v['D5'].'-'.$v['D6'].'<br>Ganhador(es): '.$v['ACERTO'].'</h3>
                <h3>Sugestão: ';
                
                $ct = 6;
                foreach($a as $k=>$v){
                    $o .= $k;
                    $ct --;
                    if($ct == 0) break;
                    $o .= '-';
                }

                $o .= '</h3>
                <h2>Frequência de Sorteio</h2>
		<p>Concursos pesquisados: '.$total.' </p><table cellspacing="0">
                <tr><th>ranking</th><th>dezena</th><th>sorteios</th></tr>';
                
                $ct = 0; //position
                $coll = -1; //collun
		foreach($a as $k=>$v){
			$ct ++;
                        $coll ++;
                        if($coll == 12) {
                            $coll = 0;
                            $o .= '</table><table cellspacing="0"><tr><th>ranking</th><th>dezena</th><th>sorteios</th></tr>';}
			$o .= '<tr><td>'.$ct.'º</td><td>'.$k.'</td><td>'.$v.'</td></tr>';
		}		
		return $o . '</table><p>'.date('Y/d/m H:i:s').'</p>';
	}



	// privates functions ===================[begin]

	//transcorder for TD table
	private function getTd($str){
		$pointer = $i = 0;
		$outFile = '';
		$data = array();
		while($i <= strlen($str)){
			$i = strpos($str, '<td>', $pointer);
			if($i !== false) {
				$in = strpos($str, '</td>', $i);
				if($in === false) {
					$this->error  ='HTM format error (TD)';
					return false;
				}
				$data[] = substr($str, $i, ($in - $i + 5));
				$pointer = $in;
			} else break;
		}
		if(isset($data[0])){
			$d[0] = $this->int($data[0]); 
			$d[1] = $this->int($data[9]);
			$d[2] = $this->int($data[2]);
			$d[3] = $this->int($data[3]);
			$d[4] = $this->int($data[4]);
			$d[5] = $this->int($data[5]);
			$d[6] = $this->int($data[6]);
			$d[7] = $this->int($data[7]);
			
			return $d;
		} else return array();
	}

	//Kill file
	private function kill($file){
		if(file_exists($file)) return unlink($file);
	}

	//string to integer
	private function int($s){
		return (int)preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s);
	}

	//insert Megasena database
	private function dbInsert($data){

		$pdo = $this->db();

		//clear old data
		$pdo->exec('DELETE FROM resultados');
		
		//inserting new data
		foreach($data as $v){		
			$pdo->exec('INSERT INTO resultados (ID, ACERTO, D1, D2, D3, D4, D5, D6) VALUES ('.$v[0].','.$v[1].','.$v[2].','.$v[3].','.$v[4].','.$v[5].','.$v[6].','.$v[7].')');
		}
	}

	// privates functions ===================[end]	
}
