<?php
class EX3domWidget extends CWidget {
	
	public $tree;
	// can be "tree" or "graph"
	public $type;
	
	public function run()
	{
		// include jquery libraries
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCoreScript('jquery.ui');
		
		Yii::app()->clientScript->registerCssFile(
				Yii::app()->clientScript->getCoreScriptUrl().
				'/jui/css/base/jquery-ui.css'
		);
		
		$this->render('x3domStart', array(tree => $this->tree));
		
		$this->generateX3DOM($this->tree, 0, 0);
		
		$this->render('x3domEnd', array(tree => $this->tree));
		
		// include own javascript files
		$naviFile = Yii::app()->baseUrl.'/js/navigation.js';
		Yii::app()->clientScript->registerScriptFile($naviFile);
	}
	
	private function generateX3DOM($node, $transX, $transZ) {
		$x3dInfos = $node->getX3dInfos();
		
		$nodeWidth = $x3dInfos->bb[size][width];
		$nodeLength = $x3dInfos->bb[size][length];

		// get translation of parent
		$translation = array();
		$translation[x] = $transX;
		$translation[y] = $x3dInfos->bb[position][y];
		$translation[z] = $transZ;
	
		if ($node->level != 0) {
			$translation[x] = $translation[x] - $nodeWidth / 2;
			$translation[z] = $translation[z] - $nodeLength / 2;
		} else {
			$translation[x] = $nodeWidth / 2 - $x3dInfos->bb[size][width] / 2;
			$translation[z] = $nodeLength / 2 - $x3dInfos->bb[size][length] / 2;
		}
		
		if ($this->type == "tree") {
			$this->render('x3dTreeLayer', array(graph=>$x3dInfos, translation=>$translation));
		} else {
			$this->render('x3dGraphLayer', array(graph=>$x3dInfos, translation=>$translation));
		}
	
		// calculate values for the children nodes
		$content = TreeElement::model()->findAllByAttributes(array('parent_id'=>$node->id, 'isLeaf'=>0));
		
		foreach ($content as $key => $value) {
			$label = trim($value->label);
			
			// layout node position
			$nodePositionX = $x3dInfos->nodes[$label][position][x];
			$nodePositionZ = $x3dInfos->nodes[$label][position][z];
			if ($node->level != 0) {
				$nodePositionX = $nodePositionX + ($transX - ($nodeWidth / 2));
				$nodePositionZ = $nodePositionZ + ($transZ - ($nodeLength / 2));
			} 
			
			$this->generateX3DOM($value, $nodePositionX, $nodePositionZ);
		}
	}
}

?>