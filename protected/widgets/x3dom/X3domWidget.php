<?php
class X3domWidget extends CWidget {
		
	public $layout;
	
	public function run() {
		$this->render('x3domStart', array());
		
		$elements = BoxElement::model()->findAllByAttributes(array('layoutId'=>$this->layout->id));
		foreach ($elements as $element) {
			if ($element->type == BoxElement::$TYPE_PLATFORM) {
				$this->render('baseObjects/platform', array('element' => $element));
			} else if ($element->type == BoxElement::$TYPE_BUILDING) {
				$this->render('baseObjects/building', array('element' => $element));
			} else if ($element->type == BoxElement::$TYPE_FOOTPRINT) {
				$this->render('baseObjects/footprint', array('element' => $element));
			}
		}
		
		$edges = EdgeElement::model()->findAllByAttributes(array('layoutId'=>$this->layout->id));
		foreach ($edges as $edge) {
			$this->render('baseObjects/edge', array('edge' => $edge));
		}
		
		$this->render('x3domEnd', array());
		
		// include own javascript files
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/information.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulation.js');
		
		if ($this->layout->type == Layout::$TYPE_STRUCTURE) {
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulationTree.js');
		} else if ($this->layout->type == Layout::$TYPE_DEPENDENCY_DETAIL || $this->layout->type == Layout::$TYPE_DEPENDENCY_METRIC) {
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sidebar/manipulationGraph.js');
		}
	}
}

?>