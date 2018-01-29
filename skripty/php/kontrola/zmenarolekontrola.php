<?php
//Kontrola změny role uživatele
	session_start();
	if(!isset($_SESSION["jmeno"])) {
		echo "Chyba - nejste přihlášen!";
		return;
	} else if ($_SESSION["typ"] != "admin") {
		echo "Chyba - nemáte oprávnění!";
		return;
	}
	
	try {
		$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
		PDO::ATTR_PERSISTENT => true,
		PDO::ATTR_EMULATE_PREPARES => false, 
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
	} catch(PDOException $e) {
		echo "Chyba - nepodařilo se připojit k databázi!";
		return;
	}
	
	$jmeno = $_POST["jmeno"];
	$typ = $_POST["typ"];
	//existuje vůbec ?
	$uzivatel = $db->prepare("SELECT * FROM uzivatele WHERE uzivatelske_jmeno = ?");
	$uzivatel->execute(array($jmeno));
	//ano ? super !
	if($uzivatel->rowCount() > 0){
		$row = $uzivatel->fetch(PDO::FETCH_ASSOC);
		if($typ=="uzivatel" ||$typ=="recenzent") {
			$update = $db->prepare("UPDATE uzivatele SET typ = ? WHERE uzivatelske_jmeno = ?");
			if($update->execute(array($typ,$jmeno)))
				echo 1;
		} else {
			echo "Chyba - Zadáváte roli, která neexistuje!";
			return;
		}
	//ne ? škoda.
	} else {
		echo "Chyba - uživatel neexistuje!";
		return;
	}        
 ?>
