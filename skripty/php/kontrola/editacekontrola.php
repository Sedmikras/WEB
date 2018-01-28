<?php 
//kontroluje editovani prispevku - jestli uzivatelem pozadovane hodnoty jsou validni pro zapis do databaze
// predevsim, jestli uz neexistuje prispevek s takovym nazvem
session_start();
if($_SESSION["typ"]!="uzivatel"){
	echo "nemáte oprávnění";
	return;
}
try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo "Nepodařilo se připojit k databázi";
		   return;
	}                                                             
   
   $nazev = $_POST['nazev'];
   $id = $_POST['id'];
   $autori = $_POST['autori'];
   $abstrakt =  $_POST['abstrakt'];
   

    
    
    $kontrola = $db->prepare("SELECT * FROM prispevky WHERE nazev = ?");
	$kontrola->execute(array($nazev));
	
    
    $old = $db->prepare("SELECT * FROM prispevky WHERE ID =  ?");
	$old->execute(array($id));
    $oldrow = $old->fetch(PDO::FETCH_ASSOC);
	
	if($nazev == $oldrow["nazev"]) {
		echo "Chyba - zadali jste stejný název jako byl původní!";
		return;
	} else {
		$save = array(); 
		if($nazev == null || $nazev == "")
        $save["nazev"] = $oldrow["nazev"];
		else $save["nazev"] = $nazev;
		if($autori == null || $autori == "")
		$save["autori"] = $oldrow["autori"];
		else $save["autori"] = $autori;
		if($abstrakt == null || $abstrakt == "")
		$save["abstract"] = $oldrow["abstract"];
		else $save["abstract"] = $abstrakt;

		$edit = $db->prepare("UPDATE prispevky SET nazev = ?, autori = ?, abstract = ?  WHERE ID = ? ");
		if($edit->execute(array($save["nazev"], $save["autori"], $save["abstract"], $id))) {
			echo true;
		} else {
			echo "Chyba - Nepodařilo se změnit hodnoty v databázi!";
		}
	}

?>
