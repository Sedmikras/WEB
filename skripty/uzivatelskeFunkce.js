function zkontrolujPravaUz(){
	return uzivatel.opravneni=="uzivatel";
}

/**
	Kontroluje formulář nahrání souboru. Poté zašle požadavek uložení do DB
*/        
function validujNahrani() {
	if (!zkontrolujPravaUz()) {
			alert("Chyba - nemáte oprávnění");
			return;}
	var form=document.forms["nahrani"];
	var soubor=document.forms["nahrani"]["soubor"].value;
	var nazev=document.forms["nahrani"]["nazev"].value;
	var autori=document.forms["nahrani"]["autori"].value;
	var abstrakt=document.forms["nahrani"]["abstrakt"].value;
	if (!soubor) {
		alert("Není vybrán žádný soubor!");
		return false;
	} else {
		var file_type = soubor.substr(soubor.lastIndexOf('.')).toLowerCase();
		if (file_type  !== '.pdf') {
			alert("Vybraný soubor není pdf !");
			return false;
		}
	}
	if (!nazev)
	  {
	  alert("Není vyplněn 'Název'!");
	  return false;
	  }
	if (!autori)
	  {
	  alert("Nejsou vyplněni 'Autoři'!");
	  return false;
	  }
	if (!abstrakt)
	  {
	  alert("Není vyplněn 'Abstrakt'!");
	  return false;
	  }

	var formData = new FormData(form);
	formData.append('nick',uzivatel.jmeno);

	$.ajax({
    type: 'POST',
    url: 'skripty/php/kontrola/nahranikontrola.php',
    data: formData,
    success: function(msg) {
		if(msg == 1) {
			zmenObsah("", "potvrzeni/nahrani_uspech.html");
			return false;
		} else if (msg == 0) {
			zmenObsah("", "potvrzeni/registrace_neuspesna.html");
			return false;
		} else {
			alert(msg);
			return false;
		}
		return false;
	},
	cache : false,
	contentType : false,
	processData : false
  });	    
}

/*
	Funkce pro uživatele - získání příspěvků ze serveru
*/
function nahrajPrispevky(odkud) {
	if(uzivatel.opravneni!="uzivatel") {
		alert("Nemáte oprávnění !"); 
		return;
	}
	$.ajax({
    type: 'GET',
    url: 'skripty/php/nactiprispevky.php',
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
	Funkce editování příspěvku. Zobrazí formulář editace
*/
function editujPrispevek(id) {
	if(uzivatel.opravneni!="uzivatel") {
		alert("Nemáte oprávnění !"); 
		return;
	}
	var dataString = 'id=' + id;
	$.ajax({
    type: 'GET',
    url: 'skripty/php/editaceprispevek.php',
	data : dataString,
    success: function(msg) {
		var obsah = $(document).find('div.obsah');
		obsah .empty();
		obsah .append(msg);	
	}
  });
}

/**
	Provede kontrolu formuláře editace příspěvku
*/
function validujEditaci(id) {
	if (!zkontrolujPravaUz()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	var form=document.forms["editace"];
	var nazev=document.forms["editace"]["nazev"].value;
	var autori=document.forms["editace"]["autori"].value;
	var abstrakt=document.forms["editace"]["abstrakt"].value;
	if (!nazev)
	  {
	  alert("Není vyplněn 'Název'!");
	  return false;
	  }
	if (!autori)
	  {
	  alert("Nejsou vyplněni 'Autoři'!");
	  return false;
	  }
	if (!abstrakt)
	  {
	  alert("Není vyplněn 'Abstrakt'!");
	  return false;
	  }
	  
	var dataString = 'id=' + id + '&nazev=' + nazev + '&autori=' + autori + '&abstrakt=' + abstrakt; 
		$.ajax({
		type: 'POST',
		url: 'skripty/php/kontrola/editacekontrola.php',
		data: dataString,
		success: function(msg) {
			if(msg==1){
				klikni();
			} else {
			alert(msg);
			return false; }
		},
	  });	    
}


