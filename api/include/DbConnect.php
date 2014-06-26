<?php


class DbConnect {
    

    // constructor
    function __construct() {
        
        // connecting to database
        $this->connect();
    }

    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }

   
 function connect() {        
      require_once 'Config.php';
        
        // Connecting to mysql database
        
      
        try {
        	// new PDO('pgsql:host=hostname;port=number;dbname=database;user=username;password=pw')
        	$conn = new PDO('pgsql:host=' . DB_HOST . ';'
        			. 'port=' . DB_PORT . ';'
        			. 'dbname=' . DB_NAME . ';'
        			. 'user=' . DB_USERNAME . ';'
        			. 'password=' . DB_PASSWORD);
        	
        	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        	
        
        } catch (PDOException $e) {
        	$fileName = basename($e->getFile(), ".php"); // File that triggers the exception
        	$lineNumber = $e->getLine();          // Line number that triggers the exception
        	die("[$fileName][$lineNumber] Database connect failed: " . $e->getMessage() . '<br />');
        }
        return $conn;
       
       // return $this->conn;
    }

   
    function close() {
        // closing db connection
    	$db=$this->connect();
        pg_close($db);
    }

}


?>
