<?php

require('../Functions/mysqli_fun.php');
require('../Functions/urlLab.php');

$absurl=urlbasesito();

if($_GET["dHjhlCaf"]=="8sdfjhG34239bj3r459srglQjhq3r"){
	$conn=sql_conn();
	$query="SELECT g.Name, g.First, g.FirstPlural, g.Text, g.Plural
			FROM Glossario g
			ORDER BY g.IdTermine";
	$glo=  $conn->query($query) or die("Query fallita: ".mysqli_error($conn));
	while($row=mysqli_fetch_row($glo)){
		for($i=0;$i<5;$i++){
			if($row[$i]!=null){
				echo"$row[$i]\n";
			}
		}
	}
}
else{
	header("Location: $absurl/error.php");
}

?>