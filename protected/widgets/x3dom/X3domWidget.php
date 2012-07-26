<?php
class X3domWidget extends CWidget {
	
	public $root;
	public $layers;
	// can be "tree" or "graph"
	public $type;
	
	public $layoutId;
	
	public function run() {
		$this->render('x3domStart', array('root' => $this->root));
		
		$elements = BoxElement::model()->findAllByAttributes(array('layoutId'=>$this->layoutId));
		foreach ($elements as $element) {
			if ($element->type == BoxElement::$TYPE_PLATFORM) {
				$this->render('baseObjects/platform', array('element' => $element));
			} else if ($element->type == BoxElement::$TYPE_BUILDING) {
				$this->render('baseObjects/building', array('element' => $element));
			} else if ($element->type == BoxElement::$TYPE_FOOTPRINT) {
				$this->render('baseObjects/footprint', array('element' => $element));
			}
		}
		
		$edges = EdgeElement::model()->findAllByAttributes(array('layoutId'=>$this->layoutId));
		foreach ($edges as $edge) {
			$this->render('baseObjects/edge', array('edge' => $edge));
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
		if ($this->type == "tree") {
			// nothing more to do here
		} else {
			$x3dInfos = $node->getX3dInfos();
			
			$this->render('x3dGraphLayer', array('graph'=>$x3dInfos));
		}
	}
}

?>