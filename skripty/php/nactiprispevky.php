<?php 
//vrátí všechny příspěvky příslušného uživatele
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
	echo "<p style=\"margin:5px\">Přidat 
			<a onclick='zmenObsah(\"this\",\"obsah/nahrani.html\")'>další příspěvek</a>.
		</p>
		<hr id=\"odrazka\" />
			<table class=\"usertable\">
				<tr class=\"nadpis\">
					<td>Název</td>
					<td>Autoři</td>
					<td>Hodnocení</td>
					<td>Smazat</td>
                </tr>";
      
      //vypise obsah uzivatelske tabulky - vsechny jeho prispevky
      //napred pres echo nadpisy a pote vola metodu pro vypis radku, dokud je neco v promenne,
      //do ktere drive byly ulozeny zaznamy, vytazene z databaze
		$prispevky = $db->prepare("SELECT * FROM prispevky WHERE uzivatelske_jmeno = ?");
		$prispevky->execute(array($_SESSION['jmeno']));          
        while($row = $prispevky->fetch(PDO::FETCH_ASSOC)) {
          
			echo '<tr><td><a onclick=\'editujPrispevek('.$row['ID'].')\'>'.$row['nazev'].'</a></td>        
              <td>'.$row['autori'].'</td>
              <td>'.$row['celkove_hodnoceni'].'</td>
              <td><a onclick=\'smazPrispevek('.$row['ID'].')\'>
              <img src="images/smazat16.png" alt="smazat" title="smazat příspěvek" /></a></td></tr>';
		}        
     echo "</table>
	 <div class=\"error jmeno\"></div>
	 <div class=\"succes\"></div>";
    
?>