// Nese informace o uživateli. Jméno a oprávnění
var uzivatel;

/**
	Inizializace stránky - načtení session z php.
*/
function initialize() {
	if(!uzivatel) {
		uzivatel = new Object();
	}
	$.ajax({
    type: 'GET',
    url: 'skripty/php/nacitani.php',
    success: function(msg) {
		if(msg) {
			var autentikace = msg.trim();
			autentikace = autentikace.split(";");
			nastavUzivatele(autentikace[0], autentikace[1]);
		} else {
			$('td.itemact:first').click();
		}
	}
  });
};
/**
	Nastaví obsah na html stránce
*/
function zmenObsah(odkud, obsah) {
	if(odkud) {
	$(document).find('td.itemact').attr('class', 'item');
	$(odkud).attr('class','itemact'); }
	$(document).find('div.obsah').empty();
	$(document).find('div.obsah').load(obsah);
}


/**
	Obsluha loginu - login nesmí být prázdný
*/
function prihlaseni() {
	if(uzivatel.jmeno) {
		logout();
	}
	var nick=document.forms["prihlas"]["nick"].value;
	var heslo=document.forms["prihlas"]["heslo"].value;
	if(!nick) {
		showError('div.error.jmeno',"Chyba - Zadejte jméno!");
		return false;
	}
	if(!heslo) {
		showError('div.error.heslo',"Chyba - Zadejte heslo!"); 
		return false;
	}
	prihlaseniDB(nick,heslo);
}


/**
	Kontrola loginu vůči databázi
*/
function prihlaseniDB(jmeno, heslo) {
	
	var dataString = 'jmeno=' + jmeno + '&heslo=' + heslo
	$.ajax({
    type: 'POST',
    url: 'skripty/php/kontrola/prihlasenikontrola.php',
    data: dataString,
    success: function(msg) {
		handleIncoming(msg,jmeno);
	}
  });
}

function nactiSchvalenePrispevky(odkud) {
	$.ajax({
    type: 'GET',
    url: 'skripty/php/nactivsechnyprispevky.php',
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
	Obsluha vrácených hodnot z kontroly loginu
*/
function handleIncoming(msg,jmeno) {
	var hodnota = parseInt(msg);
	switch(hodnota) {
		case -4: showError('div.error.jmeno',"Chyba - Nepodařilo se připojit k databázi!");break;
		case -3: showError('div.error.heslo',"Chyba - Špatné heslo !");break;
		case -2: showError('div.error.jmeno',"Chyba - Špatný uživatel !");break;
		case -1: showError('div.error.jmeno',"Chyba - Uživatel není v databázi !");break;
		case 0: nastavUzivatele(jmeno, "uzivatel");break;
		case 1: nastavUzivatele(jmeno, "admin");break;
		case 2: nastavUzivatele(jmeno, "recenzent");break;
		default :break;
	}
}

/**
	Nastaví možnosti uživatele podle jeho loginu a oprávnění;
*/
function nastavUzivatele(jmeno, opravneni) {
	uzivatel.jmeno = jmeno;
	uzivatel.opravneni = opravneni;
	var popisek = $('td.uzivatel')
	popisek.empty();
	var buttonek = $('td.prihlasovani');
	buttonek.empty();
	buttonek.attr("onclick", "logout()");
	buttonek.append('<a>odhlásit</a>');
	if(opravneni == "uzivatel") {
		popisek.append('<a onclick="klikni()">' + uzivatel.jmeno + '</a>' + ' (' + uzivatel.opravneni + ')' );
		pridejASpust(opravneni);
	} else if ( opravneni === "admin") {
		popisek.append('<a onclick=klikniAdministrace()>' + uzivatel.jmeno + '</a>' + ' (' + uzivatel.opravneni + ')' );
		pridejASpust(opravneni);
	} else if (opravneni == "recenzent") {
		popisek.append('<a onclick=klikniRecenze()>' + uzivatel.jmeno + '</a>' + ' (' + uzivatel.opravneni + ')' );
		pridejASpust(opravneni);
	} else {
		return;
	}
}

/**
	Zavolá přidání uživatelských možností(podle oprávnění) a klikne na první uživatelskou možnost
*/
function pridejASpust(opravneni) {
	pridejUzivatelskeMoznosti(opravneni);
	$('tr#uzivatelske:first').children().click();
}

/**
		Přidá uživatelské možnosti do menu
*/
function pridejUzivatelskeMoznosti(opravneni){
	var menu = $('table.leve_menu');
	if(opravneni=="uzivatel") {
		menu.append('<tr id="uzivatelske"><td class="item" onclick=\'zmenObsah(this,"obsah/nahrani.html")\'><a></a>NAHRÁT ČLÁNEK</td></tr>');
		menu.append('<tr id="uzivatelske"><td class="item"  onclick=\'nahrajPrispevky(this)\'><a></a>MOJE PŘÍSPĚVKY</td></tr>');
	} else if(opravneni == "admin") {
		menu.append('<tr id="uzivatelske"><td class="item" id="uzivatelske" onclick=\'nactiAdministraci(this)\'><a></a>SPRÁVA ČLÁNKŮ</td></tr>');
		menu.append('<tr id="uzivatelske"><td class="item" id="uzivatelske" onclick=\'nactiUzivatele(this)\'><a></a>SPRÁVA UŽIVATELŮ</td></tr>');
	} else if(opravneni == "recenzent") {
		menu.append('<tr id="uzivatelske"><td class="item"  onclick=\'nactiClanky(this)\'><a></a>RECENZE</td></tr>');
	}
	
}

/**
	Odebere uźivatelské možnosti z menu
*/
function odeberUzivatelskeMoznosti(){
	$('tr#uzivatelske').detach();
}

/**
	Odhlášení
*/
function logout() {
	uzivatel.jmeno = null;
	uzivatel.opravneni = null;
	var popisek = $('td.uzivatel')
	popisek.empty();
	popisek.append("Nepřihlášen");
	var buttonek = $('td.prihlasovani');
	buttonek.empty();
	buttonek.append('<a>přihlásit</a>');
	buttonek.attr("onclick", '$(document).find("td.itemact").attr("class", "item");zmenObsah("","obsah/prihlaseni.html")');
	odeberUzivatelskeMoznosti();
	$('td.item:first').click();
	$.ajax({url: 'skripty/php/odhlaseni.php', succes : function(msg){
		alert(msg);
	}});
}

/**
	Zobrazí chybovou hlášku
*/
function showError(selektor, zprava) {
	$('div.error:contains("Chyba")').empty();
	var hlaska = $(selektor);
	hlaska.empty();
	hlaska.append(zprava);
}

/**
	Klikne na nahrání příspěvků
*/
function klikni(){
	$('td[onclick="nahrajPrispevky(this)"]').click();
}
/**
	Klikne na správu článků
*/
function klikniAdministrace() {
	$('td[onclick="nactiAdministraci(this)"]').click();
}

/**
	Klikne na vypsání článků pro recenze
*/
function klikniRecenze() {
	$('td[onclick="nactiClanky(this)"]').click();
}

