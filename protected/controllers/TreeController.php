<?php

class TreeController extends BaseX3dController {
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		$startTime = $this->getTime();
		
		$result = $this->loadFiletoDb();
		$rootId = $result['rootId'];

		// STEP 2: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_TREE);
		$root = LayerElement::model()->findByPk($rootId);
		$root->accept($layout);
		
		// STEP 3: calculate absolute translations
		Yii::app()->layerX3dCalculator->calculate($root);
		$layers = LayerElement::model()->findAll();
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$this->render('index', array('root' => $root, 'layers'=>$layers));
	}
	
}
