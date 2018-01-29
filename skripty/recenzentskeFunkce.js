//zkontroluje zda-li je přihlášen recenzent
function zkontrolujPravaRec(){
	return uzivatel.opravneni=="recenzent";
}

/**
	Načte články které mají být recenzovány z databáze
*/
function nactiClanky(odkud) {
	if (!zkontrolujPravaRec()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	$.ajax({
    type: 'GET',
    url: 'skripty/php/ukazclanky.php',
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
	Načte obsah článku a recenzovací formulář z databáze
*/
function recenzuj(id) {
	if (!zkontrolujPravaRec()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	$.ajax({
    type: 'GET',
    url: 'skripty/php/zobrazrecenzi.php',
	data : 'id=' + id,
    success: function(msg) {
		var obsah = $(document).find('div.obsah');
		obsah .empty();
		obsah .append(msg);
	}
  });
}

/**
	Provede kontrolu provedené recenze - zda-li byla vyplněna všechna kritéria a pošle recenzi k uložení
*/
function validujRecenzi(id) {
	if (!zkontrolujPravaRec()) {
		alert("Chyba - nemáte oprávnění");
		return;}
	var originalita=$('select#r1.selectrec option:selected').text();
	var tema=$('select#r2.selectrec option:selected').text();
	var technika=$('select#r3.selectrec option:selected').text();
	var jazyk=$('select#r4.selectrec option:selected').text();
	var doporuceni=$('select#r5.selectrec option:selected').text();
	var poznamka=$('textarea#r6').val();
	
	var dataString = 'id=' + id + '&rec1=' + originalita +  '&rec2=' + tema +  '&rec3=' + technika +  '&rec4=' + jazyk +  '&rec5=' + doporuceni + '&rec6=' + poznamka;
	$.ajax({
    type: 'POST',
	data: dataString,
    url: 'skripty/php/kontrola/recenzekontrola.php',
    success: function(msg) {
		if(msg==1) {} else {
			alert(msg);
			return false;
		}
	}
  });
	nactiClanky();
}