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
	header('Content-Disposition: attachment; filename="tracciamentoRequisitiClassi.tex"');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate');
	
	$conn=sql_conn();
	//$query_ord="CALL sortForest('Requisiti')";
	$query_requi="SELECT DISTINCT r.CodAuto, r.IdRequisito
				FROM (_MapRequisiti h JOIN Requisiti r ON h.CodAuto=r.CodAuto) JOIN RequisitiClasse rc ON r.CodAuto=rc.CodReq
				ORDER BY h.Position";
	//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
	$requi=$conn->query($query_requi) or die("Query fallita: ".mysqli_error($conn));
echo<<<END
\\subsection{Tracciamento Requisiti-Classi}
\\normalsize
\\begin{longtable}{|>{\centering}m{3cm}|m{10cm}<{\centering}|}
\\hline 
\\textbf{Requisito} & \\textbf{Classi}\\\
\\hline
\\endhead
END;
	while($row_requi=mysqli_fetch_row($requi)){
		requisitiClassiTex($conn, $row_requi);
	}
echo<<<END

\\caption[Tracciamento Requisiti-Classi]{Tracciamento Requisiti-Classi}
\\label{tabella:requi-class}
\\end{longtable}
\\clearpage

END;
}
?>