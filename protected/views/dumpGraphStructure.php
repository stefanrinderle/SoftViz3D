<?php 

DumpGraphToTable($graph);

function DumpGraphToTable($graph) {
    $iBorderColor = str_pad ( mt_rand ( 0, 999999 ), 6, '0', STR_PAD_LEFT ); 

    print ( '<table cellpadding="0" cellspacing="5" ' ); 
    print ( 'style="border: 4px solid #' . $iBorderColor . '; '); 
    print ( 'font-family: Courier New; font-size: .9em;">' ); 

    if ( ! $graph instanceOf GraphComponent ) 
    { 
      return; 
    } 

    print ( '<tr>' ); 

    print ( '<td title="' . $graph->label . '" style="text-align: center;' ); 
    print ( ' font-weight: bold; border: 2px solid #000; padding:' ); 
    print ( ' 3px; background-color: #ccc;" colspan="2">' . $graph->label . '</td>' ); 

    print ( '</tr>' ); 

    // content nodes
    foreach ( $graph->content as $graphElement ) 
    { 
      print ( '<tr>' ); 

      print ( '<td title="' . $graphElement->label . '" style="text-align: ' );
      print ( 'center; border: 2px solid #000; padding: 3px;">' );
      
      if ($graphElement instanceOf Node) {
      		
      		DumpGraphToTable ( $graphElement );
			
      		print ("<td>");
      		print ( '<table cellpadding="0" cellspacing="5" ' ); 
		    print ( 'style="border: 4px solid #' . $iBorderColor . '; '); 
		    print ( 'font-family: Courier New; font-size: .9em;">' ); 
      		foreach ($graphElement->flatEdges as $edge) {
      			print ("<tr><td>");
      			print($edge->label);
      			print ("</tr></td>");
      		}
      		print ("</table>");
      		print ("</td>");
      } else {
      	print ($graphElement->label);
      	print ( '</td><td></td>' );
      }
      
      print ( '</tr>' ); 
    } 

    print ( '</table>' ); 
}
?>