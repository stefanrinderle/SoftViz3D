<?php

class TreeController extends BaseX3dController {
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		/* reset layout database */
		BoxElement::model()->deleteAll();
		
		$startTime = $this->getTime();
		
		$result = $this->loadFiletoDb();
		$rootId = $result['rootId'];

		// STEP 2: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_TREE);
		$root = InputNode::model()->findByPk($rootId);
		$root->accept($layout);
		
		// STEP 3: calculate absolute translations
		Yii::app()->layerX3dCalculator->calculate($root);
		$layers = InputNode::model()->findAll();
		
		// STEP 3: calculate absolute translations
		Yii::app()->newLayerX3dCalculator->calculate($rootId);
		//$layers = InputNode::model()->findAll();
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$layoutId = 1;
		$this->render('index', array('root' => $root, 'layers'=>$layers, 'layoutId' => $layoutId));
	}
	
}
