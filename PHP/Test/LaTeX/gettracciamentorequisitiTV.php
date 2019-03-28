<?php

require('../../Functions/mysqli_fun.php');
require('../../Functions/urlLab.php');

session_start();

$absurl=urlbasesito();

if(empty($_SESSION['user'])){
	header("Location: $absurl/error.php");
}
else{
	header('Content-type: application/x-tex');
	header('Content-Disposition: attachment; filename="tracciamentoRequisitiTV.tex"');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate');
	
	$conn=sql_conn();
	//$query_ord="CALL sortForest('Requisiti')";
	$query_requi="SELECT r.IdRequisito, CONCAT('TV',SUBSTRING(r.IdRequisito,2))
			   FROM (_MapRequisiti h JOIN Requisiti r ON h.CodAuto=r.CodAuto) JOIN Test t ON r.CodAuto=t.Requisito
			   WHERE t.Tipo='Validazione'
			   ORDER BY h.Position";
	//$ord=mysqli_query($query_ord,$conn) or die("Query fallita: ".mysqli_error($conn));
	$requi=$conn->query($query_requi) or die("Query fallita: ".mysqli_error($conn));
echo<<<END
\\subsection{Tracciamento Requisiti-Test di Validazione}
\\normalsize
\\begin{longtable}{|>{\centering}m{5cm}|m{5cm}<{\centering}|}
\\hline 
\\textbf{Requisito} & \\textbf{Test}\\\
\\hline
\\endhead
END;
	while($row_requi=mysqli_fetch_row($requi)){
echo<<<END

$row_requi[0] & \\hyperlink{{$row_requi[1]}}{{$row_requi[1]}}\\\ \\hline
END;
	}
echo<<<END

\\caption[Tracciamento Requisiti-Test di Validazione]{Tracciamento Requisiti-Test di Validazione}
\\label{tabella:requi-tv}
\\end{longtable}
\\clearpage

END;
}
?>