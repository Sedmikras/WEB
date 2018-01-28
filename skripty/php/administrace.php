<?php
//Zobrazí formulář s příspěvky k aministraci
session_start();	

function vypisPrispevekAdmin($prispevek, $db) {
	$recenze1 = array();
       
       for($x = 0;$x < 3;$x++)
       {
        $recenze1[$x][0] = "nepřiřazen";
        for($y = 1; $y < 7;$y++)
        $recenze1[$x][$y] = "-";
       }
       
	$pomocnepole['recenzenti'] = '<a onclick=\'nactiRecenzenty('.$prispevek['ID'].')\'>Přiřadit recenzenty</a>'; 
       
       if($prispevek['schvalen'] == 0) $pomocnepole['rozhodnuti'] = '<img src="obr/smazat40.png" alt="neschváleno" title="příspěvek čeká schválení" />';
       else $pomocnepole['rozhodnuti'] = '<img src="obr/potvrdit40.png" alt="schváleno" title="příspěvek schválen" />'; 
       
		
        $pomocnepole['smazat'] = '<a onclick=\'smazPrispevek('.$prispevek['ID'].'); klikniAdministrace()\'>
		<img src="obr/smazat16.png" alt="smazat" title="smazat příspěvek" /></a>';
        $pomocnepole['schvalit'] = '<a onclick=\'schvalPrispevek('.$prispevek['ID'].'); klikniAdministrace()\'>
		<img src="obr/potvrdit16.gif" alt="schválit" title="schválit příspěvek" /></a>';

       
       $hodnoceni = $db->prepare("SELECT * FROM recenze WHERE nazev = ?");  
	   if($hodnoceni->execute(array($prispevek["nazev"]))) {
		   for($int = 0; $int < 3;$int++)
            
            {
              $radka1 = $hodnoceni->fetch(PDO::FETCH_ASSOC);
              
              if(isset($radka1['recenzent']))
            {
			  $celejmeno = $db->prepare("SELECT * FROM uzivatele WHERE uzivatelske_jmeno = ?");
			  $celejmeno->execute(array($radka1['recenzent']));
              $lajna = $celejmeno->fetch(PDO::FETCH_ASSOC);
              $recenze1[$int][0] = $lajna['jmeno']." ".$lajna['prijmeni'];
              $recenze1[$int][1] = $radka1['horiginalita'];
              $recenze1[$int][2] = $radka1['htema'];
              $recenze1[$int][3] = $radka1['htechkvalita'];
              $recenze1[$int][4] = $radka1['hjazykkvalita'];
              $recenze1[$int][5] = $radka1['hdoporuceni'];
              $recenze1[$int][6] = $radka1['celkove_hodnoceni'];
              $pomocnepole['recenzenti'] = "Recenzenti přiřazeni";
              if($prispevek['schvalen'] == 0 && $prispevek['recenzovan'] == 1)
              $pomocnepole['recenzenti'] = "Recenze hotové!";
              else if($prispevek['schvalen'] == 1 && $prispevek['recenzovan'] == 1)
              $pomocnepole['recenzenti'] = "Již schváleno!";
              
            }
              
            }
	   }
       
        $vnitrek = '
        <tr>
           <td rowspan="3"><a  target="_blank" href="'.$prispevek['cesta'].'">'.$prispevek['nazev'].'</a></td>        
           <td rowspan="3">'.$prispevek['autori'].'</td>
           <td class="odkazlast" rowspan="3">'.$pomocnepole['smazat'].''.$pomocnepole['schvalit'].'<br />'.$pomocnepole['recenzenti'].'</td>
          <td class="recenze">'.$recenze1[0][0].'</td>    
          <td class="recenze">'.$recenze1[0][1].'</td>
          <td class="recenze">'.$recenze1[0][2].'</td>
          <td class="recenze">'.$recenze1[0][3].'</td>
          <td class="recenze">'.$recenze1[0][4].'</td>
          <td class="recenze">'.$recenze1[0][5].'</td>
          <td class="recenze">'.$recenze1[0][6].'</td>
          <td rowspan="3">'.$pomocnepole['rozhodnuti'].'</td>
        </tr>';
        
        for($r = 1; $r < 3; $r++)
        {  
            $trida = "recenze";
            if($r == 2) $trida = "recenzelast";
            $vnitrek .=
            '<tr>
				<td class="'.$trida.'">'.$recenze1[$r][0].'</td>
				<td class="'.$trida.'">'.$recenze1[$r][1].'</td>
				<td class="'.$trida.'">'.$recenze1[$r][2].'</td>
				<td class="'.$trida.'">'.$recenze1[$r][3].'</td>
				<td class="'.$trida.'">'.$recenze1[$r][4].'</td>
				<td class="'.$trida.'">'.$recenze1[$r][5].'</td>
				<td class="'.$trida.'">'.$recenze1[$r][6].'</td>
			</tr>';
          }

		return $vnitrek;
}

try {
				$db = new \PDO('mysql:host=localhost;dbname=sp_web;charset=utf8', 'root', 'A5C3b29f4' , [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false, 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]); 
		} catch(PDOException $e) {
		   echo "Chyba - nepodařilo se připojit k databázi!";
			return;
		}
		
if($_SESSION["typ"] != "admin") {
	echo "Nemáte oprávnění !";
	return;
}

if(!isset($_SESSION["jmeno"])) {
	echo "Nejste přihlášen !";
	return;
}

$sth = $db->prepare("SELECT * FROM prispevky ORDER BY schvalen ASC");		
$sth->execute();

echo "<div id=\"chyba\" class=\"chyba\"></div>";
echo " <table class=\"admintable\">
      
        <tr class=\"nadpis\">
                <td rowspan=\"2\">Název</td>
                <td rowspan=\"2\">Autoři</td>
                <td rowspan=\"2\">Akce</td>
                <td colspan=\"7\" class=\"nadpis2\">Recenze <a style=\"font-size:10px;\" href=\"#vysvetlivky\"> (legenda)</a></td>
                <td rowspan=\"2\">rozhodnutí</td>
                </tr>
                <tr class=\"nadpisrec\">
                
               <td> kdo ?  </td><td> O </td><td>  T </td><td>  Tk </td><td>  J </td><td>  D </td><td>  C </td>
                
                </tr>";
		
		while($row = $sth->fetch(PDO::FETCH_ASSOC))
			echo vypisPrispevekAdmin($row, $db);
 

			echo  "</table>
    
    <ul id=\"vysvetlivky\">
        <li><b>Legenda</b></li>
        <li>O = organizace</li>
        <li>T = téma</li>
        <li>Tk = technická kvalita</li>
        <li>J = jazyková kvalita</li>
        <li>D = doporučení</li>
        <li>C = průměr hodnocení</li>
    
    </ul>"
	
?>