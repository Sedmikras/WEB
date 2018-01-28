<?php
//kontrola registračního formuláře
	$nick = $_POST['nick'];
	$heslo = $_POST['heslo'];
	$jmeno = $_POST['jmeno'];
	$prijmeni = $_POST['prijmeni'];
	
	try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo "Nelze se připojit k databázi!";
		   return;
	}
	   
	//Jestli uz existuje uzivatel
    $stmt = $db->prepare("SELECT * FROM uzivatele WHERE uzivatelske_jmeno = ?");
	if($stmt->execute(array($nick))) {
	} else {
		echo "Nelze se připojit k databázi !";
		return;
	}
    //jestli existuje uzivatel se zadanym jmenem
    if($stmt->rowCount() > 0) {
       echo "Jméno se shoduje, zkuste jiné";
     } else {
		//pokud je vse v poradku, vlozi do db noveho uzivatele se vsemi jeho udaji     
		$stmt = $db->prepare("INSERT INTO uzivatele (uzivatelske_jmeno, heslo, jmeno, prijmeni, typ) VALUES ((?) , (?) , (?), (?), (?))");
		if($stmt->execute(array($nick, $heslo, $jmeno, $prijmeni, "uzivatel"))) {
			echo true;
		} else {
			echo false;
		}			
	}
?>
                                             