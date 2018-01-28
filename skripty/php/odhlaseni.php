<?php 
    //odhlasi uzivatele a znici session
    session_start();
    $_SESSION = array();
    session_destroy();
	echo "odhlasen";
?>

