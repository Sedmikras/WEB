<?php 
//vrátí formulář k editaci příspěvku
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

//vybere z db aktualni udaje o prispevku, ktery se uzivatel chysta editovat,
//aby mohly byt vypsany do formulare

    $prispevek = $db->query("SELECT * FROM prispevky WHERE ID=".$_GET['id']);
    $row = $prispevek->fetch(PDO::FETCH_ASSOC);

//pokud uz je prispevek schvalen administratorem, nastavi tlacitku "ulozit"
//jinou adresu - a to takovou, ze je uzivatel jejim prostrednictvim seznamen
//s faktem, ze schvalenty prispevek jiz nelze upravit     
      if($row['schvalen'] == 1) {
		echo "Příspěvek už byl schválen a proto nejde upravit !";
		return;
	  }

	echo '<form name="editace" action="" method="post" enctype="multipart/form-data" onsubmit="event.preventDefault(); validujEditaci('.$_GET['id'].');">
    <table class="formtable">
    <tr>
      <td>
        Název: <input type="text" name="nazev" value="';
		echo $row['nazev'];
		echo '"/>
      </td>
    </tr>
    <tr>
      <td>
        Autoři: <input type="text" name="autori" value="'; echo $row['autori']; echo '"/>
      </td>
    </tr>
    <tr>                      
      <td>
        Abstract:
      </td>
    </tr>
    <tr>
      <td>
        <textarea cols="40" rows="10" style="resize:none" name="abstrakt">'; echo $row['abstract']; echo '</textarea>
      </td>
    </tr>
    <tr>
      <td>
        <input id="uploadbutton" type="submit" name="submit" value="Uložit" />
      </td>
    </tr>
    </table>


  </form>
  
  <hr id="odrazka" />
  
  <p>
  
    Přejít zpět na <a onclick="klikni()">seznam příspěvků</a>.<br />
    Nebo raději přidat <a onclick="$(\'tr#uzivatelske:first\').children().click();">další příspěvek</a>.
  
  </p>';
  
 ?>