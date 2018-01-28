<?php 
//Smaže příspěvek z databáze - pro uźivatele i pro admina
session_start();
error_reporting(0);
if($_SESSION["typ"]=="uzivatel" || $_SESSION["typ"]=="admin") {
	$id = $_GET['id'];
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
	
	$query = $db->prepare("SELECT * FROM prispevky WHERE ID = ?");
	$query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $ret = $row['nazev']; 
	$path = $row['cesta'];
	$relative_path = "../../".$path;

	//pokud neni nikdo prihlasen, nebo se o mazani pokousi nekdo neopravnene,
	//mazani se neprovede a funkce vrati chybovou hodnotu
	
	if($row['uzivatelske_jmeno'] != $_SESSION['jmeno'] && $_SESSION['typ'] == "uzivatel") {
		echo "Chyba - Nepovolený přístup!";return;
	} else if($_SESSION["typ"] == "admin" && $row['recenzovan'] != 1) {
		echo "Chyba - příspěvek nebyl recenzován!";
	} else {
		$r = $db->prepare("DELETE FROM recenze WHERE nazev = ?");
		if($r->execute(array($row['nazev']))) {
			$p = $db->prepare("DELETE FROM prispevky WHERE ID = ?");
			if($p->execute(array($id))) {
				if(unlink($relative_path)) 
					echo true;
				else {
					echo "Chyba - Soubor neexistuje, záznam bude smazán";
					return;
				}
			} else {
				echo "Chyba - nepovedlo se smazat příspěvek!";
				return;
			}
			}  else {
				//pokud se nepovede smazat prispevek, nebo jemu nalezici recenze, vrati metoda false
				echo "Chyba - nepovedlo se smazat příspěvek!";
				return;
			}
	}
} else {
	echo "nemáte oprávnění";
	return;
}

?>