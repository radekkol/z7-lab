<?php
ini_set( 'display_errors', 'Off' ); 
session_start();
$log2 = $_SESSION['login'];
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
	echo "<input type=submit value='Zaloguj!'>";
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
$log = $_POST["login"];
if($_GET["wyloguj"]=="tak"){
	$_SESSION["zalogowany"]=0;
	echo "Zostales wylogowany z serwisu";
}
if($_SESSION["zalogowany"]!=1){
	if(!empty($_POST["login"]) && !empty($_POST["haslo"])){
		if(mysql_num_rows(mysql_query("SELECT * from users where user = '".htmlspecialchars($_POST["login"])."' AND pass = '".htmlspecialchars($_POST['haslo'])."'"))){
			$data = new DateTime();
			$data = date('Y-m-d');
			$godzina = new DateTime();
			$godzina = date('H:i:s');
			$ip = $_SERVER["REMOTE_ADDR"];
			$query1 = mysql_query("INSERT INTO logi (idLogUsers,Nazwa,Data,Czas,IP) VALUES (NULL,'$log','$data','$godzina','$ip')") or die('Blad zapytania');
			echo "Zalogowano poprawnie. 
			<a href='index.php'>Przejdz do strony glownej</a>";
			$_SESSION["zalogowany"]=1;
			$_SESSION['login']=$_POST['login'];
			}
		else echo PokazLogin("Podaj poprawne dane!");
		}
	else PokazLogin();
}
else{

echo "<h2>Witaj uzytkowniku   $log2 </h2>"; 

?>


<br><br>

<br><a href='index.php?wyloguj=tak'>wyloguj sie</a>
<?php
}
?>

</body>
</html>
<?php mysql_close($connection); 
?>