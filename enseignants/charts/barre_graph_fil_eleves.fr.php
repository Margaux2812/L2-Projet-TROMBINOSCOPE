<?php
require_once ('../../jpgraph/src/jpgraph.php');
require_once ('../../jpgraph/src/jpgraph_bar.php');
include('functions.inc.php');

/*On récupère les informations*/
$listFilieres = getFilieres();
$nbElevesManquants = array();
$nbElevesReel = array();
$nbEnTrop = array();
$labels = array();
		
	for($i=0; $i<count($listFilieres); $i++){
		$filierefullname = str_replace(' ', '_',$listFilieres[$i]); 
		$effectif = getEffectif($filierefullname);
		
		$TD_de_filiere = getTD($filierefullname);
		
		/*Si la filiere a des TD alors on calcule la différence entre le nombre d'inscrits et l'effectif
		sinon, on regarde les eleves présents directement dans le dossier*/
		if(count($TD_de_filiere)>0){	
			$nbEleves = 0;
			$effectif = 0;
			for($j=0; $j<count($TD_de_filiere); $j++){
				$nbEleves += count(getEleves($filierefullname, $TD_de_filiere[$j]));
				$effectif += getEffectif($filierefullname, $TD_de_filiere[$j]);
			}
		}else{
			$nbEleves = count(getEleves($filierefullname));
			$effectif = getEffectif($filierefullname);
		}
		
		$difference = $effectif - $nbEleves;
		
		if($difference<0){
			array_push($nbEnTrop, abs($difference));
			array_push($nbElevesManquants, 0);
			array_push($nbElevesReel, $effectif);
		}else{
			array_push($nbEnTrop, 0);
			array_push($nbElevesManquants, $difference);
			array_push($nbElevesReel, $nbEleves);
		}	
		array_push($labels, $listFilieres[$i]);
	}
	
$graph = new Graph(500,500);
$graph->SetMargin(40,20,50,170); 
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($labels);
$graph->xaxis->SetLabelAngle(50);

$b1plot = new BarPlot($nbElevesReel);

$b2plot = new BarPlot($nbElevesManquants);

$b3plot = new BarPlot($nbEnTrop);
 
$gbplot = new AccBarPlot(array($b1plot,$b2plot, $b3plot));
 
$graph->Add($gbplot);

$graph->title->Set("Part d'élèves inscrits dans les filières");
$graph->title->SetMargin(10);

$b1plot->SetFillColor("#00b33c");
$b1plot->SetColor("#00b33c");
$b1plot->SetLegend("Elèves inscrits dans la filière");

$b2plot->SetFillColor("#d9d9d9");
$b2plot->SetColor("#b3b3b3");
$b2plot->SetLegend("Elèves manquants dans la filière");

$b3plot->SetFillColor("#ff8080");
$b3plot->SetColor("#ff0000");
$b3plot->SetLegend("Elèves en trop dans la filière");

// Display the graph
$graph->legend->SetLayout(LEGEND_VERT);
$graph->Stroke();
?>