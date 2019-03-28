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
	header('Content-Disposition: attachment; filename="tracciamentoClassiRequisiti.tex"');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate');
	
	$conn=sql_conn();
	$query_cl="SELECT DISTINCT c.CodAuto, c.PrefixNome
				FROM Classe c JOIN RequisitiClasse rc ON c.CodAuto=rc.CodClass
				ORDER BY c.PrefixNome";
	$cl=$conn->query($query_cl) or die("Query fallita: ".mysqli_error($conn));
echo<<<END
\\subsection{Tracciamento Classi-Requisiti}
\\normalsize
\\begin{longtable}{|>{\centering}m{10cm}|m{3cm}<{\centering}|}
\\hline 
\\textbf{Classe} & \\textbf{Requisiti}\\\
\\hline
\\endhead
END;
	//$query_ord="CALL sortForest('Requisiti')";
	//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
	while($row_cl=mysqli_fetch_row($cl)){
		classiRequisitiTex($conn, $row_cl);
	}
echo<<<END

\\caption[Tracciamento Classi-Requisiti]{Tracciamento Classi-Requisiti}
\\label{tabella:class-requi}
\\end{longtable}
\\clearpage

END;
}
?>