<?php

class TreeController extends BaseProjectFileController {
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		/* reset layout database */
		BoxElement::model()->deleteAll();
		
		$startTime = $this->getTime();
		
		$rootId = $this->loadFiletoDb();

		// STEP 2: calculate the view layout
		
		$view = new StructureView();
		$visitor = new LayoutVisitor($view);
		$root = InputNode::model()->findByPk($rootId);
		$root->accept($visitor);
		
		// STEP 3: calculate absolute translations
		Yii::app()->absolutePositionCalculator->calculate($rootId, $view);
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$layoutId = 1;
		$this->render('index', array('layoutId' => $layoutId));
	}
	
}
