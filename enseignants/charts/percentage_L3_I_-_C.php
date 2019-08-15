<?php
				require_once ('../../jpgraph/src/jpgraph.php');
				require_once ('../../jpgraph/src/jpgraph_pie.php');

				$data = array(0,40);
				
				$graph = new PieGraph(200,200);
				$graph->SetShadow();
				 
				$graph->title->Set("L3 I - C");
				 
				$p1 = new PiePlot($data);
				$graph->Add($p1);
				$p1->SetSliceColors(array("blue", "#cccccc"));
				$graph->Stroke();

				?>