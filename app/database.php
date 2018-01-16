<?php

class ConsultaController {
	protected $container;
	protected $dbname;
	protected $port;
	protected $flag_terminal;
	protected $log;
	protected $db;

	public function __construct($container,$type){
		$this->container = $container;
        $this->dbname = $container->get('settings')["db"]["dbname"];
        $this->log = $this->container->logger;//MONOLOG
        $this->db = $this->container['settings']['db'];
		if($type == "cli"){
	        $this->port = ":3306";
	        $this->flag_terminal = true;
	    }
	    if($type == "gui"){
	    	$this->port = "";
	        $this->flag_terminal = false;
	    } 
    }
    private function connect(){
    	try {
			$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
			$pdo = new PDO('mysql:host='.$this->db['servername'].$this->port/*.';dbname='.$db['dbname']*/, $this->db['username'], $this->db['password'], $options);
			$pdo->exec('SET NAMES "'.$this->db['charset'].'"');
			return $pdo;
		} catch(\Exception $ex){
			return $ex->getMessage();
		}
    }
	private function executeQuery($query,$params=null){
		//PDO
		$con = $this->connect();
		$this->log->debug(json_encode(array("result" => array("content" => $query, "date" => date('Y-m-d H:i:s')))));
		//SI EL FLAG ES TRUE, ES PORQUE EL CODIGO SE ESTÁ EJECUTANDO DESDE LA TERMINAL, POR ESO TIRAMOS LOS VAR_DUMP
		if($this->flag_terminal)
			var_dump($query);//VAR_DUMP QUE IRÁ IMPRIMIENDO LAS QUERY
		//SI EL PARAMS ES DISTINTO DE NULL ES PORQUE SERÁ UNA SENTENCIA PREPARADA
		if($params != null){
			try{
				$stmt=$con->prepare($query);//OJO CON ATTR_CURSOR AND CURSOR_FWONLY, ¿QUE HACEN AQUÍ?
				if($params == "*")//SELECT WITHOUT WHERE
					$stmt->execute();
				else// SELECT WITH WHERE
					$stmt->execute($params);

				if(explode(" ", $query)[0] == "SELECT"){ //SI LA CONSULTA ES DE TIPO SELECT, ESTE SERA EL ÚNICO RESULT QUE RESPONDERÁ EL SERVER
					$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

					$this->log->debug(json_encode(array("result" => array("content" => $result, "date" => date('Y-m-d H:i:s')))));
					return $result;
				}
			}
			catch(PDOException $e){
				$this->log->debug(json_encode(array("error" => array("content" => $e->getMessage(), "date" => date('Y-m-d H:i:s')))));
			}
		}
		else{
			try {
				//PDO
				$result = $con->query($query);
			} catch (PDOException $e) {
				$this->log->debug(json_encode(array("error" => array("content" => $e->getMessage(), "date" => date('Y-m-d H:i:s')))));
			}
		}
	}
	private function schemaExists($dbname) {
		$con = $this->connect();
	    // Try a select statement against the table
	    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
	    try {
	        $stmt = $con->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname' ");
	        return (bool) $stmt->fetchColumn();
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        $this->log->debug(json_encode(array("error" => array("content" => $e->getMessage(), "date" => date('Y-m-d H:i:s')))));
	        return false;
	    }
	}
	public function buildCreate($type_create,$object_create){
		if($type_create == "SCHEMA")
			$this->executeQuery("CREATE DATABASE IF NOT EXISTS $object_create"); //IF NOT EXISTS IS DATABASE, THAT CREATE

		if($type_create == "TABLE"){ //OBJECT_CREATE LIKE ["TABLENAME","OBJECT TABLE"]
			$query_create_table = "CREATE TABLE IF NOT EXISTS $this->dbname.$object_create[0] (";
			$c = 0;
			foreach ($object_create[1] as $key => $value) {
				if($c == 0){ //SE CREA LA TABLA CON LA PRIMERA COLUMNA
					$query_create_table = $query_create_table.$key." ".$value.")";
					$this->executeQuery($query_create_table);
				}
				else  //EN EL CASO QUE NO SEA LA PRIMERA COLUMNA SE VAN AÑADIENDO A LA TABLA
					$this->buildCreate("COLUMN",[$object_create[0],$key,$value]);
				$c = $c + 1;
			}
		}
		if($type_create == "COLUMN"){ //OBJECT_CREATE LIKE ["TABLENAME","NAMECOLUMNS","OBJECT COLUMNS"]
			if($object_create[1] == "FOREIGN"){ //EN EL CASO QUE SE AGREGUE UNA LLAVE FORANEA NO ES NECESARIO EL SELECT
				$exp = explode(" ",$object_create[2])[0]." ".explode(" ",$object_create[2])[1]." ".explode(" ",$object_create[2])[2]." ";
				$exp .= $this->dbname.".".explode(" ",$object_create[2])[3];
				$this->executeQuery("ALTER TABLE $this->dbname.$object_create[0] ADD FOREIGN $exp"); //IF NOT EXISTS THAT CREATE
			}
			else{
				$column = $this->executeQuery("SELECT $this->dbname.$object_create[1] FROM $object_create[0]"); //VERIFY IF COLUMN EXIST
				if (!$column)
					$this->executeQuery("ALTER TABLE $this->dbname.$object_create[0] ADD $object_create[1] $object_create[2]"); //IF NOT EXISTS SO CREATE
			}
		}
	}
	public function buildSelect($table_name,$columns,$where_select){
		$query_select = "SELECT ";
		//FILL COLUMNS FOR GET
		foreach ($columns as $c) {
			$query_select .= $c;
			if(array_search($c,$columns) != count($columns) - 1) $query_select .= ",";
		}
		if($where_select != "*"){//GET
			//INTERMEDIATE
			$query_select .= " FROM $this->dbname.$table_name WHERE ";
			//FILL VALUES WHERE
			end($where_select);
			$last_key = key($where_select);
			foreach ($where_select as $key => $value) {
				$query_select .= $key . "=" . ":" . $key;
				if($key != $last_key) $query_select .= " AND ";
			}
			return $this->executeQuery($query_select,$where_select);
		}
		else{//GET ALL SELECT
			//INTERMEDIATE
			$query_select .= " FROM $this->dbname.$table_name";
			return $this->executeQuery($query_select,"*");
		}
	}
	public function buildUpdate($table_name,$columns_update,$where_update){
		$query_update = "UPDATE $this->dbname.$table_name SET ";
		//FILL COLUMNS FOR UPDATE
		end($columns_update);
		$last_key = key($columns_update);
		foreach ($columns_update as $key => $value) {
			$query_update .= $key . "=" . ":" . $key;
			if($key != $last_key) $query_update .= " , ";
		}
		//INTERMEDIATE
		$query_update .= " WHERE ";
		//FILL VALUES WHERE
		end($where_update);
		$last_key = key($where_update);
		foreach ($where_update as $key => $value) {
			$query_update .= $key . "=" . ":" . $key;
			if($key != $last_key) $query_update .= " AND ";
		}
		return $this->executeQuery($query_update,array_merge($columns_update,$where_update));
	}
	public function buildDelete($type_delete,$object_delete){
		if($type_delete == "SCHEMA"){
			$this->buildBackup($object_delete,[],null,".sql"); //GENERATE BACKUP TABLES
			$this->executeQuery("DROP DATABASE IF EXISTS $object_delete"); //DELETE DATABASE
		}
		if($type_delete == "TABLE");
		if($type_delete == "COLUMN");
		if($type_delete == "ROW"){
			//SET OF VARIABLES
			$table_name = $object_delete['table_name'];
			$where_delete = $object_delete['where_delete'];

			$query_delete = "DELETE FROM $this->dbname.$table_name WHERE ";
			//FILL VALUES WHERE
			end($where_delete);
			$last_key = key($where_delete);
			foreach ($where_delete as $key => $value) {
				$query_delete .= $key . "=" . ":" . $key;
				if($key != $last_key) $query_delete .= " AND ";
			}
			return $this->executeQuery($query_delete,$where_delete);
		}
	}
	public function buildInsert($table_name,$object_insert){

		$columns_query = "(";
		$values_query = "(";
		foreach ($object_insert as $column => $value) {
			$columns_query .= $column . ",";
			$values_query .= ":". $column . ",";
		}
		$columns_query = substr($columns_query,0,strlen($columns_query)-1) . ")";
		$values_query = substr($values_query,0,strlen($values_query)-1) . ")";

		$this->executeQuery("INSERT INTO $this->dbname.$table_name $columns_query VALUES $values_query",$object_insert);

		/*$id_insert = $_SERVER["con"]->lastInsertId();
		$this->buildSelect($db_name,$table_name,array("*"),array("id" => $id_insert));*/
	}
	public function buildSchema($modelDB){
		//ERASE THE OLD SCHEMA AND GENERATE BACKUP THE OLD SCHEMA
		$this->buildDelete("SCHEMA",$modelDB["dbname"]);
		//CREATE THE NEW SCHEMA
		$this->buildCreate("SCHEMA",$modelDB["dbname"]);
		//CREATE TABLES
		foreach ($modelDB["tables"] as $key => $value) {
			$this->buildCreate("TABLE",[$key,$value]);
		}
		//INSERT SEEDS
		foreach ($modelDB["seeds"] as $key => $value) {
			for($i = 0 ; $i < count($value) ; $i++)
				$this->buildInsert($key,$value[$i]);
		}
		//INSERT FOREING KEY
		foreach ($modelDB["foreign"] as $key => $value) {
			$this->buildCreate("COLUMN",[$key,"FOREIGN",$value]);
		}
	}
	private function buildBackup($dbname,$tables,$compression,$format) {
 		//VERIFICAMOS QUE EXISTA EL SCHEMA ANTES DE HACER EL BACKUP, EN EL CASO QUE SEA LA PRIMERA CARGA DEL SCHEMA
		if($this->schemaExists($dbname)){
			$con = $this->connect();
			$con->query('USE '.$dbname);
			$con->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL );
			//Script Variables
			$BACKUP_PATH = DUMP;
			$date = date("Y-m-d-h-m-s");
			$olddir =  getcwd();
			chdir( __DIR__."/../db/backups/");
			if(!file_exists($date)){
			    var_dump("Created ".$date." backup folder");
			    $cmd = "mkdir ".$date;
			    exec($cmd);
			}
			chdir($date);
			//array of all database field types which just take numbers
			$numtypes = array('tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal', 'real');

			//get all of the tables
			if(empty($tables)) {
			    $pstm1 = $con->query('SHOW TABLES');
			    while($row = $pstm1->fetch(PDO::FETCH_NUM))
			        $tables[] = $row[0];
			}
			else
			    $tables = is_array($tables) ? $tables : explode(',',$tables);

			foreach($tables as $table) {
			    //create/open files
			    if($format == "csv") {
			        $filename = $table.".csv";
			        $handle = fopen($filename,"w");
			    }
			    else{
			        if($compression) {
			            $filename = $table.".sql.gz";
			            $zp = gzopen($filename,"wb9");
			        }
			        else {
			            $filename = $table.".sql";
			            $handle = fopen($filename,"w");
			        }
			    }
			    $result = $con->query("SELECT * FROM $table");
			    $num_fields = $result->columnCount();
			    $num_rows = $result->rowCount();
			    $return = "";
			    $return .= 'DROP TABLE IF EXISTS `'.$table.'`;';

			    //table structure
			    $pstm2 = $con->query("SHOW CREATE TABLE $table");
			    $row2 = $pstm2->fetch(PDO::FETCH_NUM);
			    $ifnotexists = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $row2[1]);
			    $return .= "\n\n".$ifnotexists.";\n\n";

			    if($format <> "csv") {
			        if($compression) gzwrite($zp, $return);
			        else fwrite($handle,$return);
			        }
			    $return = "";

			    //insert values
			    if($num_rows) {
			        $return = 'INSERT INTO `'."$table"."` (";
			        $pstm3 = $con->query("SHOW COLUMNS FROM $table");
			        $count = 0;
			        $type = array();

			        while($rows = $pstm3->fetch(PDO::FETCH_NUM)) {
			            if(stripos($rows[1], '(')) {
			                $type[$table][] = stristr($rows[1], '(', true);
			                }
			            else $type[$table][] = $rows[1];

			            $return .= "`".$rows[0]."`";
			            $count++;
			            if($count < ($pstm3->rowCount())) {
			                $return .= ", ";
			                }
			            }
			        $return .= ")".' VALUES';
			        if($format <> "csv") {
			            if($compression) gzwrite($zp, $return);
			            else fwrite($handle,$return);
			            }
			        $return = "";
			        }
			    $count = 0;
			    while($row = $result->fetch(PDO::FETCH_NUM)) {
			        if($format <> "csv") $return = "\n\t(";
			        for($j=0; $j < $num_fields; $j++) {
			            //$row[$j] = preg_replace("\n","\\n",$row[$j]);
			            if(isset($row[$j])) {
			                if($format == "csv") $return .= '"'.$row[$j].'"';
			                else {
			                    //if number, take away "". else leave as string
			                    if((in_array($type[$table][$j],$numtypes)) && (!empty($row[$j])))
			                        $return .= $row[$j];
			                    else
			                        $return .= $con->quote($row[$j]);
			                    }
			                }
			            else {
			                if($format == "csv") $return .= '';
			                else $return .= 'NULL';
			                }
			            if($j < ($num_fields-1)) $return .= ',';
			            }
			        $count++;
			        if($format == "csv") $return .= "\n";
			        else {
			            if($count < ($result->rowCount()))
			                $return .= "),";
			            else $return .= ");";
			            }
			        if($format == "csv") fwrite($handle,$return);
			        else {
			            if($compression) gzwrite($zp, $return);
			            else fwrite($handle,$return);
			            }
			        $return = "";
			        }
			    $return = "\n\n-- ------------------------------------------------ \n\n";

			    if($format <> "csv") {
			        if($compression) gzwrite($zp, $return);
			        else fwrite($handle,$return);
			        }
			    $return = "";

			    /*$error1 = $pstm2->errorInfo();
			    $error2 = $pstm3->errorInfo();
			    $error3 = $result->errorInfo();
			    echo $error1[2];
			    echo $error2[2];
			    echo $error3[2];*/

			    if($format == "csv") fclose($handle);
			    else {
			        if($compression) gzclose($zp);
			        else fclose($handle);
			    }
			    $filesize = filesize($filename);
			}
			chdir($olddir);
			return;
		}
	}
}