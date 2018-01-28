<?php
	//kontrola nahrávání souborů
	session_start();
	if($_SESSION["typ"]!="uzivatel"){
		echo "nemáte oprávnění";
		return;
	}
	$nick = $_SESSION['jmeno'];
	$soubor = $_FILES["soubor"]["name"];
    $nazev = $_POST['nazev'];
	$autori = $_POST['autori'];
	$abstrakt = $_POST['abstrakt'];
	$nazev_souboru = basename($soubor);
	
	try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo "Nelze se připojit k databázi!";
	}
	$kontrola = $db->prepare("SELECT * FROM prispevky WHERE nazev = ?");
	if($kontrola->execute(array($nazev))) {
		
	} else {
		echo "Nelze se připojit k databázi!";
	}
    
    //jestli uz existuje prispevek s timto nazvem
    if($row = $kontrola->fetch(PDO::FETCH_ASSOC)) {
		echo "$nazev již existuje";
    } else {
			$basepath = "../../../";
            $abstract_path = $basepath."/upload/".$nick."/" .$nazev_souboru;
			$real_path =  "upload/".$nick."/" .$nazev_souboru;
          
          //jestli uz existuje v uzivatelove slozce stejne pojmenovany pdf soubor
            if (file_exists($abstract_path)) {
				echo "$nazev_souboru již byl Vámi nahrán";
            }
      
            
            //pokud je vse v poradku, ulozi do databaze udaj o prispevku a pdf soubor presune do uzivatelovy slozky
            else {
      
              $temp = explode(".", $_FILES["soubor"]["name"]);
              
                if (!file_exists($basepath.'upload/'.$nick)) {
                   mkdir($basepath.'upload/'.$nick, 0777, true);
                   }
            
              move_uploaded_file($_FILES["soubor"]["tmp_name"], $abstract_path);
              
               $query = $db->prepare("INSERT INTO prispevky (uzivatelske_jmeno, nazev, autori, cesta, abstract, recenzovan)VALUES((?), (?), (?), (?), (?), 0)");
				if($query->execute(array($nick, $nazev, $autori, $real_path, $abstrakt))) {
					echo true;
				} else {
					unlink($abstract_path);  
					echo false;
				}
                
           }    
           
    }
    
?>
