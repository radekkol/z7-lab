<?php
ini_set( 'display_errors', 'Off' ); 

//£¹czenie z serwerem

$connection = @mysql_connect('serwer1699338.home.pl', '21777739_kolenski', 'Machiavelli5')
or die('Brak po³¹czenia z serwerem MySQL.<br />B³¹d: '.mysql_error());


//£¹czenie z baz¹ danych


$db = @mysql_select_db('21777739_kolenski', $connection) 
or die('Nie moge polaczyc sie z baza danych<br />B³¹d: '.mysql_error());  

function ShowForm($komunikat=""){	//funkcja wyœwietlaj¹ca formularz rejestracyjny
	echo "$komunikat<br>";
	echo "<form action='rejestracja.php' method=post>";
	echo "Login: <input type=text name=login><br>";
	echo "Haslo: <input type=password name=haslo><br>";
	echo "<input type=hidden value='1' name=send>";
	echo "<input type=submit value='Zarejestruj mnie'>";
	echo "<br><br><a href='index.php'>Powrot do strony glownej</a>";
	echo "</form>";
}
?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<title>Formularz rejestracyjny</title>
</head>
<body>
<?php

if($_POST["send"]==1){	//sprawdzanie czy formularz zosta³ wys³any
	if(!empty($_POST["login"]) && !empty($_POST["haslo"])){	//oraz czy uzupe³niono wszystkie dane
		if(mysql_num_rows(mysql_query("SELECT * from users where user='".htmlspecialchars($_POST["login"]."'"))))ShowForm("Uzytkownik o podanym loginie juz istnieje!!!"); // sprawdzanie czy u¿ytkownik o podanej nazwie ju¿ istnieje
		else{
			mysql_query("INSERT into users values('', '".htmlspecialchars($_POST["login"])."', '".htmlspecialchars($_POST['haslo'])."',0)"); // zapisywanie rekordu do bazy
			echo "Rejestracja przebiegla pomyslnie. Mozesz teraz przejsc do <a href='index.php'>strony glownej</a> i sie zalogowac.";
			}
	}
	else ShowForm("Nie uzupelniono wszystkich pol!!!");
}
else ShowForm();
mysql_close($connection); //zamykanie po³¹czenia z baz¹
?>
</body>
</html>