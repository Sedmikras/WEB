<?php
	//kontrola přiřazení recenzentům ke konkrétnímu příspěvku
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


                
	//jestli jsou ve vsech selectech vybrani nejaci recenzenti  
	if($_POST["rec1"] == "vyberte recenzenta" || $_POST["rec2"] == "vyberte recenzenta" || $_POST["rec3"] == "vyberte recenzenta") {
		echo "Chyba - Nebyli vybráni všichni recenzenti!";
		return;
	}
                   
	if($_POST["rec1"] == $_POST["rec2"]|| $_POST["rec1"] == $_POST["rec3"]|| $_POST["rec3"] == $_POST["rec2"]) {
		return;
		echo "Chyba - V každém selectu musí být jiný recenzent!";
	}

	$nazev = $db->prepare("SELECT * FROM prispevky WHERE id = ?");
	$zmena = $db->prepare("UPDATE prispevky SET celkove_hodnoceni = 'hodnotí se' WHERE id = ?");
	$nazev->execute(array($_POST['id']));
	$zmena->execute(array($_POST['id']));              
	if($nazev == false || $zmena = false) {
		echo "Chyba - Přiřazení se nepovedlo!";
		return;
	}
              
	$row = $nazev->fetch(PDO::FETCH_ASSOC);
             
	//pokud se vse povede, vytvori se pozadavek na databazi, ktery do ni nasledne zapise nove recenze
	for($int = 1; $int < 4;$int++) {
		$pozadavek = $db->prepare("INSERT INTO recenze (ID, nazev, recenzent)VALUES((?), (?), (?))");
		if($pozadavek->execute(array($row['ID'], $row['nazev'], $_POST["rec".$int]))) {
		} else {
			echo "Chyba - Přiřazení se nepovedlo!";
			return;
		}
	}    
	echo true;


?>