<?php
			require_once ('../../jpgraph/src/jpgraph.php');
			require_once ('../../jpgraph/src/jpgraph_pie.php');

			$data = array(10,6);
			
			$graph = new PieGraph(200,200, 'auto');
			$graph->SetShadow();
			 
			$graph->title->Set("L1 MIPI");
			 
			$p1 = new PiePlotC($data);
			$p1->value->SetFont(FF_ARIAL, FS_NORMAL, 0);
			$p1->midtitle->Set("63%");
			$p1->midtitle->SetFont(FF_ARIAL,FS_NORMAL,10);
			$graph->Add($p1);
			$p1->SetSliceColors(array("skyblue2", "#cccccc"));
			$graph->Stroke();

			?>