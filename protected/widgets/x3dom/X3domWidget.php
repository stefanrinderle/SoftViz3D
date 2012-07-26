<?php
class X3domWidget extends CWidget {
	
	public static $TYPE_TREE = "tree";
	public static $TYPE_GRAPH = "graph";
	
	public $layoutId;
	public $type;
	
	public function run() {
		$this->render('x3domStart', array());
		
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
		
		$this->render('x3domEnd', array());
		
		// include own javascript files
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/sidebar.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/navigation.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/information.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulation.js');
		
		if ($this->type == X3domWidget::$TYPE_TREE) {
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulationTree.js');
		} else if ($this->type == X3domWidget::$TYPE_GRAPH) {
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulationGraph.js');
		}
	}
}

?>