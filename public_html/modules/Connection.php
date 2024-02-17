<?php
	class Connection {	
	
		function __construct() {

		}

		function connect() {
			
			$servername = "localhost";
			$dbname="my_db";
			$username = "";
			$password = "";
			$connection = null;
			
			try {
				
				$this->$connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				$this->$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $this->$connection;
				
			} catch(PDOException $e) {
				
				echo "Error: " . $e->getMessage();
				
			}
			
		}
		
	}
?>
