<?php
require_once ('../../jpgraph/src/jpgraph.php');
require_once ('../../jpgraph/src/jpgraph_pie.php');
include('functions.inc.php');


/*On récupère les informations
La légende sera un array avec le nom de filieres
et $data sera un array avec l'effectif de chaque filière*/
$listFilieres = getFilieres();
$legend = array();
$data = array();	
						
for($i=0; $i<count($listFilieres); $i++){
	$filierefullname = str_replace(' ', '_',$listFilieres[$i]); 
	$effectif = getEffectif($filierefullname);
				
	array_push($legend, $listFilieres[$i]);
	array_push($data, $effectif);
}
							
$graph = new PieGraph(500,500);
$graph->SetShadow();
				 
$graph->title->Set('Les filières du Département d\'informatique');
				 
$p1 = new PiePlot($data);
$graph->Add($p1);
$p1->SetLegends($legend);
$graph->Stroke();
?>