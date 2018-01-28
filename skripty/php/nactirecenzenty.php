<?php 
    //Načte všechny recenzenty z DB
    session_start();
    
	if($_SESSION["typ"]!="admin") {
		echo "Chyba - Nemáte oprávnění!";
		return;
	} else {
		try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo "Chyba - nepodařilo se připojit k databázi!";
			return;
		}
		$query = $db->prepare("SELECT uzivatelske_jmeno FROM uzivatele WHERE typ = 'recenzent'");
		if($query->execute()) {
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				echo $row["uzivatelske_jmeno"];
				echo ";";
			}
		} else {
			echo "Chyba - nepodařilo se připojit k databázi!";
		}
	}
?>