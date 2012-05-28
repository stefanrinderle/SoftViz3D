<?php

class TreeX3dCalculator extends AbstractX3dCalculator
{
	protected function adjustNode($node, $depth) {
			$result = array(
					'name'=>$node[label],
					'size'=>array('width'=> 0, 'height'=> 0, 'length' => 0),
					'position'=>array('x' => $node['attr']['pos'][0],
							'y' => $depth * $this->layerDepth,
							'z' => $node['attr']['pos'][1]),
					'colour'=>array('r'=>0, 'g'=>0, 'b'=>0),
					'transparency'=>0
			);
	
		return $result;
	}
}