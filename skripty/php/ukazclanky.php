<?php 
//Vypíše články k recenzování
session_start();

function vypisRecenzi($row, $db){
		$pr = $db->query("SELECT * FROM prispevky WHERE ID=".$row['ID']);
      $r = $pr->fetch(PDO::FETCH_ASSOC)['schvalen'];  
      if($r != 1) {
		$html = '<tr>';
		$html.= '<td>
					<a onclick=\'recenzuj('.$row['ID'].')\'>'.$row['nazev'].'</a>
				</td>';
		$html.= '<td>
					'.$row['celkove_hodnoceni'].'
				</td>';
		$html.= '</tr>';
      return $html; } else {
		  return;
	  }
			  
}	


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

	echo "<table class=\"usertable\">";
      
      //vypise radky v tabulce recenzenta, napred hlavicku a pak opakovane vola fuknci pro vypis jednotlivych radku
		
        $prispevky = $db->prepare("SELECT * FROM recenze WHERE recenzent = ?");
        $prispevky->execute(array($_SESSION["jmeno"]));
        echo '<tr class="nadpis">
                <td>Název</td>
                <td>Hodnocení</td>
                </tr>';
                
                
        while($row = $prispevky->fetch(PDO::FETCH_ASSOC))
			echo vypisRecenzi($row, $db);       

		
      
     
     echo '</table>';
    
    
    echo '<span class="warning">';
      
      //vypise hlasku podle, toho co recenzent provedl
      
      
      //prispevek byl uspesne ohodnocen
         if(isset($_GET['ohodnocen']))
         {
                echo '<hr id="odrazka" />';
              echo 'Příspěvek <i>'.$_GET['ohodnocen'].'</i> byl ohodnocen.';
         
         } 
           
         // pri hodnoceni se vyskytla chyba, prispevek nebyl ohodnocen
         if(isset($_GET['err']))
         {
                echo '<hr id="odrazka" />';
              echo 'Vyskytla se chyba! Příspěvek <i>'.$_GET['err'].'</i> NEbyl ohodnocen.';
         
         
         }
            
            
            
    echo '</span>';
    

?>