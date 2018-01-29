<?php 
//vrátí všechny příspěvky příslušného uživatele
session_start();
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
	echo "<table class=\"usertable\" width=\"100%\">
				<tr class=\"nadpis\">
					<td>Název</td>
					<td>Autoři</td>
					<td>Hodnocení</td>
                </tr>";
      
      //vypise obsah uzivatelske tabulky - vsechny jeho prispevky
      //napred pres echo nadpisy a pote vola metodu pro vypis radku, dokud je neco v promenne,
      //do ktere drive byly ulozeny zaznamy, vytazene z databaze
		$prispevky = $db->query("SELECT * FROM prispevky WHERE schvalen = 1");          
        while($row = $prispevky->fetch(PDO::FETCH_ASSOC)) {
          
			echo '<tr><td><a  target="_blank" href="'.$row['cesta'].'">'.$row['nazev'].'</a></td>        
              <td>'.$row['autori'].'</td>
              <td>'.$row['celkove_hodnoceni'].'</td>
			</tr>';
		}        
     echo "</table>";
    
?>