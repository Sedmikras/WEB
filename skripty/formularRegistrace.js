/**
	Zkontroluje zda-li byla správně vyplněna všechna pole registrace. Poté zavolá příslušné php
*/        
function validujRegistraci()
{
var nick=document.forms["reg"]["nick"].value;
var heslo=document.forms["reg"]["heslo"].value;
var heslo1=document.forms["reg"]["hesloznovu"].value;
var jmeno=document.forms["reg"]["jmeno"].value;
var prijmeni=document.forms["reg"]["prijmeni"].value;
nick = nick.trim();

if (!nick)
  {
  alert("Není vyplněno 'Uživatelské jméno'!");
  return false;
  }
if (!heslo)
  {
  alert("Není vyplněno 'Heslo'!");
  return false;
  }
if (!heslo1)
  {
  alert("Není vyplněno 'Zopakujte heslo'!");
  return false;
  }
if (!jmeno)
  {
  alert("Není vyplněno 'Jméno'!");
  return false;
  }
if (!prijmeni)
  {
  alert("Není vyplněno 'Příjmení'!");
  return false;
  }
if (heslo!==heslo1) {
	alert("Hesla se neshodují !");
	return false;
}  
var dataString = 'nick=' + nick + '&heslo=' + heslo + '&jmeno=' + jmeno + '&prijmeni=' + prijmeni;
	$.ajax({
    type: 'POST',
    url: 'skripty/php/kontrola/registracekontrola.php',
    data: dataString,
    success: function(msg) {
		if(msg == 1) {
			zmenObsah("", "potvrzeni/registrace_uspesna.html");
		} else if (msg == 0) {
			zmenObsah("", "potvrzeni/registrace_neuspesna.html");
		} else {
			alert(msg);
		}
	}
  });	  
}

