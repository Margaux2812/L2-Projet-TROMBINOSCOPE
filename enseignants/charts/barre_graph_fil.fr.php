<?php
require_once ('../../jpgraph/src/jpgraph.php');
require_once ('../../jpgraph/src/jpgraph_bar.php');
include('functions.inc.php');

/*On récupère les informations*/
$listFilieres = getFilieres();
$effectifManquant = array();
$effectifReel = array();
$effectifEnTrop = array();
$labels = array();
		
	for($i=0; $i<count($listFilieres); $i++){
		$filierefullname = str_replace(' ', '_',$listFilieres[$i]); 
		$effectif = getEffectif($filierefullname);
		
		$TD_de_filiere = getTD($filierefullname);
		
		/*Si la filiere a des TD alors on calcule la somme des effectifs de ses TDS*/
		if(count($TD_de_filiere)>0){
			$effectifReelFil = 0;
			
			for($j=0; $j<count($TD_de_filiere); $j++){
				$effectifReelFil += getEffectif($filierefullname, $TD_de_filiere[$j]);
			}
			$effectifManq = $effectif - $effectifReelFil;
			$effectifRee = $effectif - $effectifManq;
			
			array_push($effectifManquant, $effectifManq);
			array_push($effectifReel, $effectifRee);
		}else{
			array_push($effectifManquant, 0);
			array_push($effectifReel, $effectif);
		}
		array_push($labels, $listFilieres[$i]);
	}
	
$graph = new Graph(500,500);
$graph->SetMargin(40,20,50,110); 
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($labels);

$b1plot = new BarPlot($effectifReel);

$b2plot = new BarPlot($effectifManquant);
 
$gbplot = new AccBarPlot(array($b1plot,$b2plot));
 
$graph->Add($gbplot);

$graph->title->Set('Les effectifs des filières');

$b1plot->SetFillColor("#00b33c");
$b1plot->SetColor("#00b33c");
$b1plot->SetLegend("Effectif Des Groupes de TD de la filière");
$b2plot->SetFillColor("#d9d9d9");
$b2plot->SetColor("#b3b3b3");
$b2plot->SetLegend("Effectif de la filière en plus");

 
// Display the graph
$graph->Stroke();