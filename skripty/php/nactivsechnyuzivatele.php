<?php 
//vrátí všechny příspěvky příslušného uživatele
session_start();
if(!isset($_SESSION["jmeno"])) {
	echo "Nejste přihlášen !";
	return;
}
if($_SESSION["typ"] != "admin") {
	echo "Nemáte oprávnění !";
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
	echo '<table class="admintable" width="100%">
				<tr class="nadpis">
					<td>Přezdívka</td>
					<td>Jméno</td>
					<td>Příjmení</td>
					<td>Oprávnění</td>
					<td>Akce</td>
                </tr>';
      
      //vypise obsah uzivatelske tabulky - vsechny jeho prispevky
      //napred pres echo nadpisy a pote vola metodu pro vypis radku, dokud je neco v promenne,
      //do ktere drive byly ulozeny zaznamy, vytazene z databaze
		$prispevky = $db->query("SELECT * FROM uzivatele");          
        while($row = $prispevky->fetch(PDO::FETCH_ASSOC)) {
          
			echo '
			<tr class="'.$row['uzivatelske_jmeno'].'">
				<td>';
				if($row['typ']!="admin") {
					echo "<a onclick=\"editujUzivatele('".$row['uzivatelske_jmeno']."','".$row['jmeno']."','".$row['prijmeni']."','".$row['typ']."')\"";
					echo "\x3E";
					echo $row['uzivatelske_jmeno'].'</a>';}
				else 
					echo $row['uzivatelske_jmeno'];
			
			echo '</td>
				<td>'.$row['jmeno'].'</td>
				<td>'.$row['prijmeni'].'</td>
				<td>'.$row['typ'].'</td>
				<td>';
				if($row['typ']!="admin"){
					echo '<a onclick="smazUzivatele(\''.$row['uzivatelske_jmeno'].'\')"><img src="obr/smazat16.png" alt="smazat" title="smazat příspěvek"/></a>';
				}
				echo '</td>
			</tr>';
		}        
     echo "</table>";
    
?>