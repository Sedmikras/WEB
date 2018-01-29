//zkontroluje zda-li je přihlášen admin
function zkontrolujPravaAdm(){
	return uzivatel.opravneni=="admin";
}

/**
	Načte formulář administrace z databáze
*/
function nactiAdministraci(odkud) {
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
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
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
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
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
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
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
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
	if (!zkontrolujPravaAdm() && !zkontrolujPravaUz()) {
		alert("Chyba - nemáte oprávnění");
		return;}
		
	var hlaska = 'Chcete opravdu smazat příspěvek ?'; 
	var vysledek = confirm(hlaska);
	if(!vysledek){
		return false;
	};   
	
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
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
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
	Načte všechny uživatele do tabulky
*/
function nactiUzivatele(odkud) {
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	$.ajax({
    type: 'GET',
    url: 'skripty/php/nactivsechnyuzivatele.php',
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
	Zkontroluje zda-li je možné smazat uživatele a zeptá se, jestli to myslíte vážně.
*/
function smazUzivatele(jmeno) {
	if(uzivatel.opravneni != "admin") {
		alert("nemáte oprávnění");
	} else {
		var hlaska = 'Chcete opravdu smazat uživatele: ' + jmeno + " ?\n POZOR : Smažete tím všechny jeho příspěvky a všechny recenze !"; 
		var vysledek = confirm(hlaska);
		if(vysledek){
		   posliSmazani(jmeno);
		} else {
		   return false;
	   };   
	}
	
}

/**
	Vytvoří formulář pro editaci role uživatele
*/
function editujUzivatele(prezdivka, jmeno, prijmeni, opravneni) {
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	var radek = '<table class="admintable" width="100%">';
	radek+= '<tbody><tr><td>' + prezdivka + '</td>';
	radek+='<td>' + jmeno + '</td>';
	radek+='<td>' + prijmeni + '</td>';
	radek+='<td> <select class="vyber" name="role">';
	if(opravneni=="uzivatel"){
		radek+='<option value="uzivatel" selected>Uživatel</option>';
		radek+='<option value="recenzent">Recenzent</option>';
	} else {
		radek+='<option value="recenzent" selected>Recenzent</option>';
		radek+='<option value="uzivatel">Uživatel</option>';
	}
	radek+='</select> </td>';
	radek+='<td>';
	radek+= "<a class=\"button\" onclick=\"posliEditaci('"+ prezdivka + "','"+opravneni+"')\" \x3E Uložit</a>";
	radek+='</td></tr></tbody></table>';
	var obsah = $(document).find('div.obsah');
	obsah.empty();
	obsah.append(radek);

}

/**
	Odešle data o editaci role uživatele do DB přes AJAX
*/
function posliEditaci(jmeno, opravneni){
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	var vybrano = $('select.vyber').val();
	var dataString = 'jmeno=' + jmeno + '&typ=' + vybrano;
	if(vybrano==opravneni) {
		$('td.itemact').click();
	} else {
		$.ajax({
		type: 'POST',
		data : dataString,
		url: 'skripty/php/kontrola/zmenarolekontrola.php',
		success: function(msg) {
		if(msg == 1) 
			$('td.itemact').click();
		else {
			alert(msg);
		}
		}
	});
	}
}

/**
	Pošle požadavek přes AJAX do databáze na smazání uživatele
*/
function posliSmazani(jmeno){
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	var dataString = 'jmeno=' + jmeno;
	$.ajax({
    type: 'POST',
	data : dataString,
    url: 'skripty/php/kontrola/smazuzivatele.php',
    success: function(msg) {
		if(msg==1){
			$('td.itemact').click();
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
	if (!zkontrolujPravaAdm()) {
		alert("Chyba - nemáte oprávnění");
		return;}
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