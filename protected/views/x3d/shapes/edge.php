<?php 

// bleibt fest
$coneHeight = 0.5;
$cylinderRadius = 0.2;

$startpoint = $startPos;
$endpoint = $endPos;

$calcX = ($startpoint[x] - $endpoint[x]);
$calcY = ($startpoint[y] - $endpoint[y]);
$calcZ = ($startpoint[z] - $endpoint[z]);
$mainEdgeHeight = sqrt(pow($calcX, 2) + pow($calcY, 2) + pow($calcZ, 2));

$coneRadius = $cylinderRadius * 2;
$cylinderHeight = $mainEdgeHeight - $coneHeight;

$edgeVektor = array('x'=>$endpoint[x] - $startpoint[x],
					'y'=>$endpoint[y] - $startpoint[y],
					'z'=>$endpoint[z] - $startpoint[z]);
$winkelVektor = array('x'=>1, 'y'=>0, 'z'=>0);

$tmp1 = (($edgeVektor[x] * $winkelVektor[x]) + ($edgeVektor[y] * $winkelVektor[y]) + ($edgeVektor[z] * $winkelVektor[z]));
$tmp2 = sqrt(pow($edgeVektor[x], 2) + pow($edgeVektor[y], 2) + pow($edgeVektor[z], 2));
$tmp3 = sqrt(pow($winkelVektor[x], 2) + pow($winkelVektor[y], 2) + pow($winkelVektor[z], 2));
$erg = acos($tmp1 / ($tmp2 * $tmp3));

$kreuzprodukt = array('x'=> $winkelVektor[y] * $edgeVektor[z] - $winkelVektor[z] * $edgeVektor[y],
					  'y'=> $winkelVektor[z] * $edgeVektor[x] - $winkelVektor[x] * $edgeVektor[z],
					  'z'=> $winkelVektor[x] * $edgeVektor[y] - $winkelVektor[y] * $edgeVektor[x]);

if ($kreuzprodukt[y] > 0) {
	$erg = -$erg;
}
?>

<Group>
	<Transform translation='<?php echo $startpoint[x] . " " . $startpoint[y] . " " . $startpoint[z] ?>'>
	      <Group>
	          <Transform translation='0 0 0' rotation="0 0 1 -1.57">
	          <Transform translation='0 0 0' rotation="1 0 0 <?php echo $erg; ?>">
	            <Transform translation='0 <?php echo $cylinderHeight / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	  	              <Material diffuseColor='1 0 0'/>
	                </Appearance>
	                <Cylinder height='<?php echo $cylinderHeight; ?>' radius="<?php echo $cylinderRadius; ?>"></Cylinder>
	            </Shape>
	            </Transform>
	            <Transform translation='0 <?php echo $cylinderHeight + $coneHeight / 2; ?> 0'>
	            <Shape>
	                <Appearance>
	                	<Material diffuseColor='1 0 0'/>
	                </Appearance>
	                <Cone bottomRadius='<?php echo $coneRadius; ?>' height='<?php echo $coneHeight; ?>'/>
	            </Shape>
	            </Transform>
	          </Transform>
	          </Transform>
	      </Group>
      </Transform>
    </Group>