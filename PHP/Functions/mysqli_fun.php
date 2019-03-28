<?php

function extract_IdRequisiti($tipo){
	$conn=sql_conn();
	$query="SELECT r.CodAuto, r.IdRequisito
			FROM _MapRequisiti h JOIN Requisiti r ON h.CodAuto=r.CodAuto
			WHERE r.Tipo='$tipo'
			ORDER BY h.Position";
	$requi=  $conn->query($query) or die("Query fallita: ".mysqli_error($conn));
	return $requi;
}

/*function die($message){
	die($_SERVER['PHP_SELF'] . ": $message<br />");
}*/

function get_info($user){
	$conn=sql_conn();
	$user = $conn->real_escape_string($user);
	$query="SELECT u.Password, u.Nome, u.Cognome
			FROM Utenti u
			WHERE u.Username='$user'";
	$query=  $conn->query($query) or die("Query fallita: ".mysqli_error($conn));
	$db=mysqli_fetch_row($query);
	return $db;
}

function sql_conn(){
	$host="INSERIRE_NOME_HOST";
	$user="INSERIRE_NOME_UTENTE_DB";
	$pwd="INSERIRE_PASSWD_DB";
	$dbname="pragmadb";
	$conn=mysqli_connect($host,$user,$pwd,$dbname)
			or die("Connessione fallita!");
//	mysqli_select_db($dbname);
	$query="SET @@session.max_sp_recursion_depth = 255";//necessario per garantire
	//max profondità possibile alle procedure ricorsive nei sistemi che non 
	//permettono di settare variabili globali
    $query=  $conn->query($query) or die("Query fallita: ".mysqli_error($conn));
	return $conn;
}

?>
