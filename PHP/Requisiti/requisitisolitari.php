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
	$conn=sql_conn();
	//$query_ord="CALL sortForest('Requisiti')";
	$query="SELECT r1.CodAuto,r1.IdRequisito,r1.Descrizione,r1.Tipo,r1.Importanza,r1.Padre,r1.Stato,r1.Soddisfatto,r1.Implementato,r1.Fonte,r2.IdRequisito,f.Nome
			FROM ((_MapRequisiti h JOIN Requisiti r1 ON h.CodAuto=r1.CodAuto) LEFT JOIN Requisiti r2 ON r1.Padre=r2.CodAuto) JOIN Fonti f ON r1.Fonte=f.CodAuto
			WHERE r1.CodAuto NOT IN (SELECT ruc.CodReq FROM RequisitiUC ruc)
			ORDER BY h.Position";
	//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
	$req=  $conn->query($query) or die("Query fallita: ".mysqli_error($conn));
	$title="Requisiti Solitari";
	startpage_builder($title);
echo<<<END

			<div id="content">
				<h2>Requisiti Solitari</h2>
				<table>
					<thead>
						<tr>
							<th>IdRequisito</th>
							<th>Descrizione</th>
							<th>Tipo</th>
							<th>Importanza</th>
							<th>Padre</th>
							<th>Stato</th>
							<th>Soddisfatto</th>
							<th>Implementato</th>
							<th>Fonte</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
END;
	while($row=mysqli_fetch_row($req)){
echo<<<END

						<tr>
END;
		requisito_table($row);
echo<<<END

							<td>
								<ul>
									<li><a class="link-color-pers" href="$absurl/Requisiti/storicorequisito.php?id=$row[0]">Storico</a></li>
									<li><a class="link-color-pers" href="$absurl/Requisiti/modificarequisito.php?id=$row[0]">Modifica</a></li>
									<li><a class="link-color-pers" href="$absurl/Requisiti/eliminarequisito.php?id=$row[0]">Elimina</a></li>
								</ul>
							</td>
						</tr>
END;
	}
echo<<<END

					</tbody>
				</table>
			</div>
END;
	endpage_builder();
}
?>