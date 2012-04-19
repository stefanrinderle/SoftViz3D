<?php

class VectorCalculator extends CApplicationComponent
{
	
	public function rotationXAxis() {
		$baseXVektor = array('x'=>1, 'y'=>0, 'z'=>0);

		$angle = Yii::app()->vectorCalculator->angle($edgeVektor, $baseXVektor);
		$crossProduct = Yii::app()->vectorCalculator->crossProduct($baseXVektor, $edgeVektor);
		
		if ($crossProduct[y] > 0) {
			$angle = -$angle;
		}
		
		return $angle;
	}
	
	public function crossProduct($vector1, $vector2) {
		return array('x'=> $vector1[y] * $vector2[z] - $vector1[z] * $vector2[y],
					 'y'=> $vector1[z] * $vector2[x] - $vector1[x] * $vector2[z],
					 'z'=> $vector1[x] * $vector2[y] - $vector1[y] * $vector2[x]);
	}
	
	public function angle($vector1, $vector2) {
		$tmp1 = $this->scalarProduct($vector1, $vector2);
		$tmp2 = $this->magnitude($vector1);
		$tmp3 = $this->magnitude($vector2);
		
		return acos($tmp1 / ($tmp2 * $tmp3));
	}
	
	public function scalarProduct($vector1, $vector2) {
		return (($vector1[x] * $vector2[x]) + ($vector1[y] * $vector2[y]) + ($vector1[z] * $vector2[z]));
	}
	
	public function magnitude($vector) {
		return sqrt(pow($vector[x], 2) + pow($vector[y], 2) + pow($vector[z], 2));
	}
}