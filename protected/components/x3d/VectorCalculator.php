<?php

class VectorCalculator extends CApplicationComponent {
	
	public static function vector($vector1, $vector2) {
		return array('x'=>$vector2[x] - $vector1[x],
					 'y'=>$vector2[y] - $vector1[y],
					 'z'=>$vector2[z] - $vector1[z]);
	}
	
	public static function rotationXAxis($vector) {
		$baseXVektor = array('x'=>1, 'y'=>0, 'z'=>0);

		$angle = VectorCalculator::angle($vector, $baseXVektor);
		$crossProduct = VectorCalculator::crossProduct($baseXVektor, $vector);
		
		if ($crossProduct[y] > 0) {
			$angle = -$angle;
		}
		
		return $angle;
	}
	
	public static function crossProduct($vector1, $vector2) {
		return array('x'=> $vector1[y] * $vector2[z] - $vector1[z] * $vector2[y],
					 'y'=> $vector1[z] * $vector2[x] - $vector1[x] * $vector2[z],
					 'z'=> $vector1[x] * $vector2[y] - $vector1[y] * $vector2[x]);
	}
	
	public static function angle($vector1, $vector2) {
		$tmp1 = VectorCalculator::scalarProduct($vector1, $vector2);
		$tmp2 = VectorCalculator::magnitude($vector1);
		$tmp3 = VectorCalculator::magnitude($vector2);
		
		return acos($tmp1 / ($tmp2 * $tmp3));
	}
	
	public static function scalarProduct($vector1, $vector2) {
		return (($vector1[x] * $vector2[x]) + ($vector1[y] * $vector2[y]) + ($vector1[z] * $vector2[z]));
	}
	
	public static function magnitude($vector) {
		return sqrt(pow($vector[x], 2) + pow($vector[y], 2) + pow($vector[z], 2));
	}
}