<?php
//Kontrola schválení příspěvku adminem
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
  //zmeni veskere udaje v databazi prislusne schvaleni ohodnoceneho prispevku

		$prispevek = $db->query("SELECT * FROM prispevky WHERE ID =".$_GET['id']);
		$row = $prispevek->fetch(PDO::FETCH_ASSOC);
		if($row['recenzovan'] != 1) {
			echo "Chyba - příspěvek nebyl recenzován!";
			return;
		}
  
		$schvalit = $db->prepare("UPDATE prispevky SET schvalen = 1 WHERE ID = ?");
		if($schvalit->execute(array($_GET['id']))) {
			$recenze = $db->prepare("SELECT * FROM recenze WHERE nazev = ?");
			$recenze->execute(array($row['nazev']));
            
            
            //vypocte prumerne hodnoceni
            $hodnoceni = 0;
            while($row = $recenze->fetch(PDO::FETCH_ASSOC))
            {
                $hodnoceni = $hodnoceni + $row['celkove_hodnoceni'];
             }
             
             $hodnoceni = $hodnoceni/3;
             $hodnoceni = round($hodnoceni, 3);
              
            $zmena = $db->query("UPDATE prispevky SET celkove_hodnoceni = '".$hodnoceni."' WHERE ID =".$_GET['id']);
          
          //zjisti jestli se zapis do db povedl
            if($zmena == null)
            {
              $schvalit = $db->query("UPDATE prispevky SET schvalen = 0 WHERE ID =".$_GET['id']);
              echo "Chyba - změny se nepodařilo zapsat do databáze";
            }
            
            else   echo true;
		} else {
			echo "Chyba - Nepodařilo se načíst příspěvek z databáze!";
		}
                

        
 ?>
