 <?php 
 //zobrazí formulář k recenzování
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
    
    $prispevek = $db->query('SELECT * FROM prispevky WHERE ID ='.$_GET['id']);
    $odkaz = $prispevek->fetch(PDO::FETCH_ASSOC)['cesta'];
  
  
    
echo ' <ul id="vysvetlivky">
        <li><b>Hodnocení</b></li>
        <li>1 = nejlepší</li>
        <li>5 = nejhorší</li>
        <li>Pokud neohodnotíte nějaké kritérium, zůstane poslední uložené.</li>
        <li>(nebo žádné v případě, že tento příspěvěk hodnotíte poprvé)</li>
    </ul>';
    
	echo '
    <hr class="odrazka" />
  
    <p>
     &gt;&gt; <a target="_blank" href='.$odkaz.'>Zobrazit příspěvek </a> &lt;&lt;
     </p>
     
    <hr class="odrazka" />
  
      <form name="recenze" method="post" action="" onsubmit="event.preventDefault() ; validujRecenzi('.$_GET['id'].');">
          <table>
              <tr>
                <td>
                Originalita:
                </td>
                 <td>
                   <select id="r1" name="rec1" class="selectrec">
                       <option value="0">vyberte hodnocení</option>
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option> 
                        <option value="4">4</option>
                        <option value="5">5</option>
                   </select>
                </td>
              </tr>
              <tr>
                <td>
                Téma:
                </td>
                <td>
                  <select id="r2" name="rec2" class="selectrec">
                       <option value="0">vyberte hodnocení</option>
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option> 
                        <option value="4">4</option>
                        <option value="5">5</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                Technická kvalita:
                </td>
                 <td>
                    <select id="r3" name="rec3" class="selectrec">
                      <option value="0">vyberte hodnocení</option>
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option> 
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                 </td>
              </tr>
                <tr> 
                <td>
                Jazyková Kvalita:
                </td>
                 <td>
                    <select id="r4" name="rec4" class="selectrec">
                        <option value="0">vyberte hodnocení</option>
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option> 
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                 </td>
              </tr>
                <tr>
                <td>
                Doporučení:
                </td>
                 <td>
                    <select id="r5" name="rec5" class="selectrec">
                        <option value="0">vyberte hodnocení</option>
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option> 
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                 </td>
              </tr>
                <tr>
                <td>
                Poznámky:
                </td>
                 <td>
                    <textarea id="r6" name="rec6" cols="30" rows="10" style="resize:none"></textarea>
                 </td>
              </tr>
              <tr>
                <td>
                    <input id="recenzebutton" type="submit" value="Uložit" /> 
                </td>
              </tr> 
          </table>    
      </form>
      
  <hr class="odrazka" />
  
  <p>
  
    Přejít zpět na <a onclick="nactiClanky()">seznam recenzí</a>.<br />
  
  </p>';
?>