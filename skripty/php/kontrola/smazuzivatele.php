<?php 
//Smaže uživatele z databáze
session_start();
error_reporting(0);
if($_SESSION["typ"]!="admin") {
	echo "Chyba - nemáte oprávnění";
	return;
}
	$jmeno = $_POST['jmeno'];
	try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo "Chyba - Nepodařilo se připojit k databázi!";
		   return;
	} 

	
	
	$stmt = $db->prepare("SELECT * FROM uzivatele WHERE uzivatelske_jmeno = ?");
	$stmt->execute(array($jmeno));
	$uzivatel = $stmt->fetch(PDO::FETCH_ASSOC);
	if($uzivatel["typ"] == "uzivatel") {
		$query = $db->prepare("SELECT nazev FROM prispevky WHERE uzivatelske_jmeno = ?");
		$query->execute(array($jmeno));
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$query2 = $db->prepare("DELETE FROM recenze WHERE nazev = ?");
			$query2->execute(array($row["nazev"]));
			$query1 = $db->prepare("DELETE FROM prispevky WHERE uzivatelske_jmeno = ?");
			$query1->execute(array($jmeno));
		}
	} else if ($uzivatel["typ"] == "admin") {
		echo "Chyba - Nemůžete smazat admina.";
		return;
	}  else if ($uzivatel["typ"] == "recenzent"){
	}
	$query = $db->prepare("DELETE FROM uzivatele WHERE uzivatelske_jmeno = ?");
	if($query->execute(array($jmeno))){
		echo true;
	} else {
		echo "Chyba - nepodařilo se smazat uživatele!";
	}

?>