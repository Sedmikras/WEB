<?php 
	//Zjistí, zda-li je nějaký uživatel přihlášen a jakou má roli
	try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		  // echo false;
	}  
	session_start();

	if (!session_id()) {
		echo 0;
	} else {
		if(!isset($_SESSION["jmeno"])) {
			
		} else if($_SESSION["typ"] == "uzivatel") {
			echo $_SESSION["jmeno"].";".$_SESSION["typ"];
		} else if($_SESSION["typ"] == "recenzent") {
			echo $_SESSION["jmeno"].";".$_SESSION["typ"];
		} else if($_SESSION["typ"] == "admin") {
			echo $_SESSION["jmeno"].";".$_SESSION["typ"];
		} else {
			echo 0;
		}  
	}
 
?>