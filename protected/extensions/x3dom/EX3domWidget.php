<?php
class EX3domWidget extends CWidget {
	
	public $tree;
	
	public function run()
	{
		$this->render('x3domStart', array(tree => $this->tree));
		
		$this->generateX3DOM($this->tree, 0, 0);
		
		$this->render('x3domEnd', array(tree => $this->tree));
	}
	
	private function generateX3DOM($node, $transX, $transZ) {
		$nodeWidth = $node->x3dInfos->bb[size][width];
		$nodeLength = $node->x3dInfos->bb[size][length];
	
		// get translation of parent
		$translation[x] = $transX;
		$translation[y] = $node->x3dInfos->bb[position][y];
		$translation[z] = $transZ;
	
		if (!$node->level == 0) {
			$translation[x] = $translation[x] - $nodeWidth / 2;
			$translation[z] = $translation[z] - $nodeLength / 2;
		}
	
		$this->render('x3dGroup', array(graph=>$node->x3dInfos, translation=>$translation));
	
		// calculate values for the children nodes
		$content = $node->content;
	
		foreach ($content as $key => $value) {
			$label = trim($value->label);
	
			// layout node position
			$nodePositionX = $node->x3dInfos->nodes[$label][position][x];
			$nodePositionZ = $node->x3dInfos->nodes[$label][position][z];
	
			if (!$node->level == 0) {
				$nodePositionX = $nodePositionX + ($transX - ($nodeWidth / 2));
				$nodePositionZ = $nodePositionZ + ($transZ - ($nodeLength / 2));
			}
			//TODO: Why is this required?
			if ($value->x3dInfos) {
				$this->generateX3DOM($value, $nodePositionX, $nodePositionZ);
			}
		}
	}
}

?>