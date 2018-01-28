 <?php 
	//kontroluje uložení recenze do databáze
	session_start();
	
	if(!isset($_SESSION["jmeno"])) {
    echo "Chyba - Nejste přihlášen ";
	return;
} 
	
if ($_SESSION["typ"] != "recenzent"){
    echo "Chyba - Nemáte oprávnéní";
	return;
}

	try {
		$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
		PDO::ATTR_PERSISTENT => true,
		PDO::ATTR_EMULATE_PREPARES => false, 
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
	} catch(PDOException $e) {
		echo "Chyba - Nelze se připojit k databázi!";
		return;
	}

//uloží recenzi do databáze	
function ulozRecenzi($row, $id, $nick, $db){
      
          $celk = 0;
          $del = 0;
        for($c = 0; $c < 5; $c++)
        {
           $celk = $celk + $row[$c];
           if($row[$c] != 0) $del++;
         }  
         if($del != 0)
          $celk = $celk / $del;
           $celk = round($celk, 2);
        
         $updt = $db->query('UPDATE recenze SET hotovo = 1,
                          horiginalita = '.$row[0].',
                          htema = '.$row[1].',
                          htechkvalita = '.$row[2].',
                          hjazykkvalita = '.$row[3].',
                          hdoporuceni = '.$row[4].',
                          poznamka = "'.$row[5].'",
                          celkove_hodnoceni = '.$celk.' 
                      WHERE recenzent = "'.$nick.'" AND ID ='.$id);
         if($updt == false) return false;
         
         
        $vsechny = $db->query('SELECT COUNT(*) FROM recenze WHERE hotovo = 1 AND ID ='.$id);
        $pocet = $vsechny->fetchColumn(); 
        if($pocet == 3) $db->query('UPDATE prispevky SET recenzovan = 1 WHERE ID='.$id);
         
         return true;
    
    
}

      //kontroluje recenzi:
      //napred vybere z db udaje o teto recenzi, pokud jiz byla provedena
      //nasledne vytvori pole do ktereho ulozi data, ktera se pozdeji budou ukladat do db
      $oldr = $db->query(' SELECT * FROM recenze WHERE recenzent ="'.$_SESSION["jmeno"].'" AND ID = '.$_POST['id']);
      $row = $oldr->fetch(PDO::FETCH_ASSOC);
      
      //pokud v _POST je nejaka hodnota, pouzije tu, pokud ne, pouzije tu, ktera jiz je v db
        $save = array();
        if($_POST['rec1'] != '0' && is_numeric($_POST['rec1'])) $save[0] = $_POST['rec1'];
          else $save[0] = $row['horiginalita'];
        if($_POST['rec2'] != '0' && is_numeric($_POST['rec2'])) $save[1] = $_POST['rec2'];
          else $save[1] = $row['htema'];
        if($_POST['rec3'] != '0' && is_numeric($_POST['rec3'])) $save[2] = $_POST['rec3'];
          else $save[2] = $row['htechkvalita'];
        if($_POST['rec4'] != '0' && is_numeric($_POST['rec4'])) $save[3] = $_POST['rec4'];
          else $save[3] = $row['hjazykkvalita'];
        if($_POST['rec5'] != '0' && is_numeric($_POST['rec5'])) $save[4] = $_POST['rec5'];
          else $save[4] = $row['hdoporuceni'];
        if($_POST['rec6'] != '' && $_POST['rec6'] != null) $save[5] = $_POST['rec6'];
          else $save[5] = $row['poznamka'];
        
        $saved = ulozRecenzi($save, $_POST['id'], $_SESSION['jmeno'], $db);
        
        if($saved == false) echo "Chyba - nepodařilo se zapsat recenzi pro soubor s názvem".$row['nazev'];
        else echo true;



?>