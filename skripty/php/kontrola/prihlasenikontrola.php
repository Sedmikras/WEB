<?php  
    $jmeno = $_POST['jmeno'];
	$heslo = $_POST['heslo'];
	
	try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo -4;
		}
		
    //kontroluje ruzne veci ohledne prihlaseni uzivatele - a to nasledujici -
		
		$stmt = $db->prepare("SELECT * FROM uzivatele WHERE uzivatelske_jmeno = ?");
		$stmt->execute(array($jmeno));

        //jestli uzivatel existuje
          if($stmt->rowCount() < 1) {
			echo -1;      
          }
          
          else {
			$row = "";
			$stmt2 = $db->prepare("SELECT * FROM uzivatele WHERE uzivatelske_jmeno = ?");
			if($stmt2->execute(array($jmeno))) {
				$row =  $stmt2->fetch(PDO::FETCH_ASSOC);
			} else {
				echo -2;
			} 
			if($row['heslo'] != ($heslo)) {
				echo -3;
            } else {
				 //pokud je vse spravne, vytvori session a nastavi do ni uzivatelovo jmeno a typ
					  session_id("SID");
                      session_start();
                      $_SESSION["jmeno"] = $row["uzivatelske_jmeno"];
                      $_SESSION["typ"] = $row['typ']; 
                      if($_SESSION["typ"] == "uzivatel")
						echo 0;
                      
                      else if($_SESSION["typ"] == "admin")
						echo 1;
                      
                      else if($_SESSION["typ"] == "recenzent")
						echo 2; 
                      //pokud se cestou neco pokazi, session se znici a prejde se na stranku oznamujici selhani
                      else {
						echo "Ničím session";
                          $_SESSION = array();
                          session_destroy();
						  echo -4;
                      
                      }
			}
                  
                          
          } 	  
?>