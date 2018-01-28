/**
	Načte formulář administrace z databáze
*/

function nactiAdministraci(odkud) {
	$.ajax({
    type: 'GET',
    url: 'skripty/php/administrace.php',
    success: function(msg) {
		var obsah = $(document).find('div.obsah');
		obsah .empty();
		obsah .append(msg);
		$('td.itemact').attr('class', 'item');
		$(odkud).attr('class','itemact');
	}
  });
}

/**
	Zkontroluje zda-li byli vyplněni všichni recenzenti ve formuláři přiřazení recenzentů a zavolá požadavek k uložení do DB
*/
function validujRecenzenti(){
	var w = document.getElementById('formprirazeni1').options[document.getElementById('formprirazeni1').selectedIndex].text;
	var x = document.getElementById('formprirazeni2').options[document.getElementById('formprirazeni2').selectedIndex].text;
	var y = document.getElementById('formprirazeni3').options[document.getElementById('formprirazeni3').selectedIndex].text;
	var id=document.forms["prirazeni"]["id"].value;
	
	if(x == "vyberte recenzenta" || y == "vyberte recenzenta" || w == "vyberte recenzenta") {
		alert("Musíte vybrat všechny recenzenty !");
		return false;
   } else if(w == x || w==y || x==y) {
		alert("Recenzenti se shodují !");
		return false;
	} else {
		var hlaska = 'Chcete opravdu příspěvku přiřadit recenzenty: \n' + w+ ", " + x + " a " + y + " ?"; 
	   var vysledek = confirm(hlaska);
	   if(vysledek){
		   posliPozadavek(w,x,y,id);
	   } 
	   else {
		   return false;
	   };   
	}
}

/**
	Zobrazí formulář přiřazení recenzentů
*/
function zobrazFormular(recenzenti, id){
	var moznosti = "";
	for( var i=0; i < recenzenti.length; i++) {
		if(recenzenti[i])
			moznosti+='<option value=' + recenzenti[i] + '>' + recenzenti[i] + '</option>';
	}
	var obsah = $('div.obsah');
	obsah.empty();
	var formular = $('<form method="post" name="prirazeni" action="" onsubmit="event.preventDefault(); validujRecenzenti();"></form>')
	formular.append('<table><tr><td></td></tr></table>');
	formular.append('<select id="formprirazeni1" name="rec1" class="selectrec"><option>vyberte recenzenta</option>' + moznosti + '</select>');
    formular.append('</td></tr><tr><td>');
    formular.append('<select id="formprirazeni2" name="rec2" class="selectrec"><option>vyberte recenzenta</option>' + moznosti + '</select>');
    formular.append('</td></tr><tr><td>');          
    formular.append('<select id="formprirazeni3" name="rec3" class="selectrec"><option>vyberte recenzenta</option>' + moznosti + '</select>');            
    formular.append('</td></tr><tr><td><input name="id" value="' + id + '" type="hidden"><input id="recenzebutton" value="Uložit" type="submit"> ');      
    formular.append('</td></tr></table>');
	obsah.append(formular);
}

/**
	Pošle požadavek k uložení příspěvku DB
*/
function schvalPrispevek(id) {
	var dataString= 'id='+id; 
	$.ajax({
    type: 'GET',
	data : dataString,
    url: 'skripty/php/kontrola/schvalkontrola.php',
    success: function(msg) {
		if(msg == 1) {
			klikniAdministrace();
		} else {
			alert(msg);
		}	
	}
  });
}

/**
	Pošle požadavek k odstranění příspěvku DB
*/
function smazPrispevek(id) {
	var dataString = 'id=' + id;
	$.ajax({
    type: 'GET',
    url: 'skripty/php/smazprispevek.php',
	data: dataString,
    success: function(msg) {
		if(msg=="1") 
			klikni();
		else
			alert(msg);
			klikni();
	}
  });
}

/**
	Pošle požadavek přiřazení recenzentů do DB
*/
function posliPozadavek(w,x,y,id) {
	var dataString = 'rec1=' + w + '&rec2=' + x + '&rec3=' + y + '&id=' + id;
	$.ajax({
    type: 'POST',
	data : dataString,
    url: 'skripty/php/kontrola/prirazenikontrola.php',
    success: function(msg) {
		if(msg==1){
			klikniAdministrace();
		} else {
			alert(msg);
		}
			
		}
	});
}

/**
	Načte recenzenty z DB
*/
function nactiRecenzenty(id){
	var recenzenti;
	$.ajax({
    type: 'GET',
    url: 'skripty/php/nactirecenzenty.php',
    success: function(msg) {
		if(msg.includes('Chyba')) {
			alert(msg);
			return;
		} else {
		recenzenti = msg;
		recenzenti = recenzenti.split(";");}
		zobrazFormular(recenzenti, id);
	}
  });
}