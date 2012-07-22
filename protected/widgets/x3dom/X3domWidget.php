<?php
class X3domWidget extends CWidget {
	
	public $root;
	public $layers;
	// can be "tree" or "graph"
	public $type;
	
	public function run() {
		$this->render('x3domStart', array('root' => $this->root));
		
		foreach ($this->layers as $layer) {
			$this->generateX3DOM($layer);
		}
		
		$this->render('x3domEnd', array('root' => $this->root));
		
		// include own javascript files
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/sidebar.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/navigation.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/information.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulation.js');
		
		if ($this->type == "tree") {
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulationTree.js');
		} else {
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulationGraph.js');
		}
	}
	
	private function generateX3DOM($node) {
		$x3dInfos = $node->getX3dInfos();
		
		if ($this->type == "tree") {
			$this->render('x3dTreeLayer', array('graph'=>$x3dInfos));
		} else {
			$this->render('x3dGraphLayer', array('graph'=>$x3dInfos));
		}
	}
}

?>