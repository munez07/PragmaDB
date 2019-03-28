<?php

require('../Functions/mysqli_fun.php');
require('../Functions/page_builder.php');
require('../Functions/urlLab.php');

session_start();

$absurl=urlbasesito();

if(empty($_SESSION['user'])){
	header("Location: $absurl/error.php");
}
else{
	$id=$_GET['id'];
	$conn=sql_conn();
	$id = $conn->real_escape_string($id);
	$query_cl="SELECT c.CodAuto, c.PrefixNome
			   FROM Classe c
			   WHERE c.CodAuto='$id'";
	$cl=$conn->query($query_cl) or die("Query fallita: ".mysqli_error($conn));
	$row_cl=mysqli_fetch_row($cl);
	if($row_cl[0]==$id){
		//$query_ord="CALL sortForest('Requisiti')";
		$query="SELECT r1.CodAuto, r1.IdRequisito
				FROM RequisitiClasse rc JOIN (_MapRequisiti h JOIN Requisiti r1 ON h.CodAuto=r1.CodAuto) ON rc.CodReq=r1.CodAuto
				WHERE rc.CodClass='$id'"; //query che carica i requisiti correlati alla classe con id = $id
		//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
		$req=  $conn->query($query) or die("Query fallita: ".mysqli_error($conn));
		$title="Requisiti Correlati - $row_cl[1]";
		startpage_builder($title);
echo<<<END

			<div id="content">
				<h2>$row_cl[1]</h2>
				<div class="widget-area-left secondary" role="complementary">
					<aside id="export" class="widget">
						<h4 class="widget-title">Link Utili</h4>
						<ul>
							<li><a class="link-color-pers" href="$absurl/Classi/classi.php">Torna a Tabella Classi</a></li>
						</ul>
					</aside>
				</div>
				<div class="widget">
					<h4 class="widget-title">Requisiti Correlati</h4>
					<p>
END;
		if($row=mysqli_fetch_row($req)){
echo<<<END
<a class="link-color-pers" href="$absurl/Requisiti/dettagliorequisito.php?id=$row[0]">$row[1]</a>
END;
			while($row=mysqli_fetch_row($req)){
echo<<<END
, <a class="link-color-pers" href="$absurl/Requisiti/dettagliorequisito.php?id=$row[0]">$row[1]</a>
END;
			}
		}
echo<<<END
</p>
                 </div>
END;
	}
	else{
		//Non ho trovato niente con questo $id
		$title="Requisiti Correlati - Classe Non Trovata";
		startpage_builder($title);
echo<<<END

			<div id="content" class="alerts">
				<h2>Errore</h2>
				<p>La classe con id "$id" non è presente nel database.</p>
END;
	}
echo<<<END

			</div>
END;
	endpage_builder();
}
?>