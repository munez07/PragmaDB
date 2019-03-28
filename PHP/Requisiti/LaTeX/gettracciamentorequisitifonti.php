<?php

require('../../Functions/get_tex.php');
require('../../Functions/mysqli_fun.php');
require('../../Functions/urlLab.php');

session_start();

$absurl=urlbasesito();

if(empty($_SESSION['user'])){
	header("Location: $absurl/error.php");
}
else{
	header('Content-type: application/x-tex');
	header('Content-Disposition: attachment; filename="tracciamentoRequisitiFonti.tex"');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate');
	
	$conn=sql_conn();
	//$query_ord="CALL sortForest('Requisiti')";
	$query_requi="SELECT r1.CodAuto,r1.IdRequisito,f.Nome
					FROM (_MapRequisiti h JOIN Requisiti r1 ON h.CodAuto=r1.CodAuto) JOIN Fonti f ON r1.Fonte=f.CodAuto
					ORDER BY h.Position";
	//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
	$requi=$conn->query($query_requi) or die("Query fallita: ".mysqli_error($conn));
echo<<<END
\\subsection{Tracciamento Requisiti-Fonti}
\\normalsize
\\begin{longtable}{|>{\centering}m{5cm}|m{5cm}<{\centering}|}
\\hline 
\\textbf{Id Requisito} & \\textbf{Fonti}\\\
\\hline
\\endhead
END;
	//$query_ord="CALL sortForest('UseCase')";
	//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
	while($row_requi=mysqli_fetch_row($requi)){
		requisitiFontiTex($conn, $row_requi);
	}
echo<<<END

\\caption[Tracciamento Requisiti-Fonti]{Tracciamento Requisiti-Fonti}
\\label{tabella:requi-fonti}
\\end{longtable}
\\clearpage

END;
}
?>