<?php
$this->breadcrumbs=array(
	'Graph Viz',
);?>

<h1>Einlesen, Layout und parsen von dot-Files</h1>

<p>Datei: <?php echo $input; ?></p>

<?php DumpArrayToTable($graph); ?>

<?php 
function DumpArrayToTable ( $arDest ) 
  { 
    // Random-Farbe fuer den Rand der Tabellen. 
    $iBorderColor = str_pad ( mt_rand ( 0, 999999 ), 6, 
                              '0', STR_PAD_LEFT ); 

    print ( '<table cellpadding="0" cellspacing="5" ' ); 
    print ( 'style="border: 4px solid #' . $iBorderColor . '; '); 
    print ( 'font-family: Courier New; font-size: .9em;">' ); 

    // Wenn kein Array, dann Input in ein Array umwandeln. 
    if ( ! is_array ( $arDest[0] ) ) 
    { 
      $arDest = array ( $arDest ); 
    } 

    print ( '<tr>' ); 

    // Schluessel des Arrays als Tabellenspaltenueberschrift. 
    foreach ( array_keys ( $arDest[0] ) as $strTmpT ) 
    { 
      $strType = 'Type: ' . gettype ( $strTmpT ); 

      print ( '<td title="' . $strType . '" style="text-align: center;' ); 
      print ( ' font-weight: bold; border: 2px solid #000; padding:' ); 
      print ( ' 3px; background-color: #ccc;">' . $strTmpT . '</td>' ); 
    } 

    print ( '</tr>' ); 

    // Array durchlaufen. 
    foreach ( $arDest as $arTmpElm ) 
    { 
      print ( '<tr>' ); 

      foreach ( $arTmpElm as $strTmpVal ) 
      { 
        $strType = 'Type: ' . gettype ( $strTmpVal ); 

        print ( '<td title="' . $strType . '" style="text-align: ' ); 
        print ( 'center; border: 2px solid #000; padding: 3px;">' ); 

        if ( is_array ( $strTmpVal ) && ! empty ( $strTmpVal ) ) 
        { 
          // Wenn ein Array in einem Array, dann 
          // rekursiv DumpArrayToTable aufrufen. 
          DumpArrayToTable ( $strTmpVal ); 
        } 
        else 
        { 
          if ( ! empty ( $strTmpVal ) ) 
          { 
            print ( $strTmpVal ); 
          } 
          else 
          { 
            // Wenn aktuelles Array-Element leer ist, 
            // dann Tabellenzelle mit "empty" markieren. 
            print ( 'empty' ); 
          } 
        } 

        print ( '</td>' ); 
      } 

      print ( '</tr>' ); 
    } 

    print ( '</table>' ); 
  } 
?>