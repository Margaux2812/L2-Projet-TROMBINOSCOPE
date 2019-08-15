<?php
			require_once ('../../jpgraph/src/jpgraph.php');
			require_once ('../../jpgraph/src/jpgraph_pie.php');

			$data = array(11,8);
			
			$graph = new PieGraph(200,200, 'auto');
			$graph->SetShadow();
			 
			$graph->title->Set("L2 MI");
			 
			$p1 = new PiePlotC($data);
			$p1->value->SetFont(FF_ARIAL, FS_NORMAL, 0);
			$p1->midtitle->Set("58%");
			$p1->midtitle->SetFont(FF_ARIAL,FS_NORMAL,10);
			$graph->Add($p1);
			$p1->SetSliceColors(array("skyblue2", "#cccccc"));
			$graph->Stroke();

			?>