<?php
ini_set( 'display_errors', 'Off' ); 
session_start();
$log3 = $_SESSION["login"];
$_SESSION["zalogowany"]; 
if(empty($_SESSION["zalogowany"]))$_SESSION["zalogowany"]=0; 

//£¹czenie z serwerem

$connection = @mysql_connect('serwer1699338.home.pl', '21777739_kolenski', 'Machiavelli5')
or die('Brak po³¹czenia z serwerem MySQL.<br />B³¹d: '.mysql_error());


//£¹czenie z baz¹ danych


$db = @mysql_select_db('21777739_kolenski', $connection) 
or die('Nie moge polaczyc sie z baza danych<br />B³¹d: '.mysql_error()); 



function PokazLogin($komunikat=""){
	echo "$komunikat<br>";
	echo "<form action='logowanie.php' method=post>";
	echo "Login: <input type=text name=login><br>";
	echo "Haslo: <input type=password name=haslo><br>";
	echo "<input type=submit value='Zaloguj sie'>";
	echo "</form>";
	}
?>

<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
</head>
<body>

<?php

if($_GET["wyloguj"]=="tak"){
	$_SESSION["zalogowany"]=0;
	echo "Zostales wylogowany z serwisu";
}
$log2 = $_POST["login"];
$dataa = new DateTime();
$dataa = date('Y-m-d');
$godzina = new DateTime();
$godzina = date('H:i:s');
$ip = $_SERVER["REMOTE_ADDR"];

if($_SESSION["zalogowany"]!=1){
	if(!empty($_POST["login"]) && !empty($_POST["haslo"])){
		if(mysql_num_rows(mysql_query("SELECT * from users where user = '".htmlspecialchars($_POST["login"])."' AND pass = '".htmlspecialchars($_POST['haslo'])."'"))){
			$query1 = mysql_query("SELECT * FROM users where user = '".htmlspecialchars($_POST["login"])."'") or die('B³¹d');
			$logowanie = mysql_fetch_array($query1);
			$ilosc = $logowanie["probylogowan"];
			echo "Zalogowano poprawnie. Aby kontynuowac kliknij <a href='logowanie.php'>TUTAJ</a>";
			$i=0;
			$_SESSION["zalogowany"]=1;
			$_SESSION['login']=$_POST['login'];
			$stan = "POZYTYWNY";
			$logowanie =mysql_query("INSERT INTO logi VALUES (NULL,'$log2','$stan','$dataa','$godzina','$ip')") or die('Blad zapytania dodania danych do tabeli logi');
			$proby = mysql_query("UPDATE users SET probylogowan='$i' WHERE user='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania z probami logowania');
			if ($ilosc != 0){
			$z= "NEGATYWNY";
			$query2 = mysql_query("SELECT * FROM logi WHERE NazwaUsera= '".htmlspecialchars($_POST["login"])."' AND StanZalogowania='$z' ORDER BY idLogUsers DESC LIMIT 1") or die('B³¹d z StanemZalogowania'); 			
			$wynik = mysql_fetch_array($query2);
			$data = $wynik["Data"];
			$czas = $wynik["Czas"];
			echo "<br><br>Ostatnie bledne logowanie bylo: $data $czas";
			}
			}
		else{
			$ip = $_SERVER["REMOTE_ADDR"];
			$stan = "NEGATYWNY";
			$logowanie=mysql_query("INSERT INTO logi VALUES (NULL,'$log2','$stan','$dataa','$godzina','$ip')") or die('Blad zapytania');
			if(mysql_num_rows(mysql_query("select * from users where user = '".htmlspecialchars($_POST["login"])."'"))){
				$query1 = mysql_query("SELECT * FROM users where user = '".htmlspecialchars($_POST["login"])."'") or die('B³¹d');
				$logowanie = mysql_fetch_array($query1);
				$ilosc = $logowanie["probylogowan"];
				if ($ilosc == 0){
				$j=1;
				echo PokazLogin("<h3>Pierwsze bledne logowanie, ponow logowanie z prawidlowymi danymi</h3>");
				$proby=mysql_query("UPDATE users SET probylogowan='$j' WHERE user='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
				}
			if ($ilosc == 1){
				$j=2;
				echo PokazLogin("<h3>Drugie bledne logowanie, ponow logowanie z prawidlowymi danymi</h3>");
				$proby=mysql_query("UPDATE users SET probylogowan='$j' WHERE user='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
			}
			if ($ilosc == 2){
				$j=3;
				echo PokazLogin("<h3>Trzecie bledne logowanie. Skontaktuj sie z administratorem aby odblokowac konto</h3>");
				$proby=mysql_query("DELETE FROM users WHERE user='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
			}
		}
		else { PokazLogin("Podaj poprawne dane!");
		}
	}
	}
	else { PokazLogin();
	}
}
else{

echo "<h2>Witaj uzytkowniku  $log3</h2>"; 
?>
<br>
<br><h3>Zalogowales sie pomyslnie! Przejdz do <a href="foldery.php">swojego katalogu</a></h3>
<br>
<br><a href='index.php?wyloguj=tak'>wyloguj sie</a>
<?php
}
?>

</body>
</html>
<?php mysql_close($connection); 
?>