<?php

class TreeController extends BaseX3dController
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		$startTime = $this->getTime();
		
		// STEP 1: Load input dot file
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		try {
			/* reset database */
			TreeElement::model()->deleteAll();
			EdgeElement::model()->deleteAll();
			
// 			$result = Yii::app()->dotFileParser->parseFile($filename);
			$result = Yii::app()->ownDotParser->parse($filename);
// 			$edges = $result[edges];
			$rootId = $result[rootId];
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
			//TODO render another layout file and exit
		}
		
		// STEP 2: Write parsed data into database
// 		Yii::app()->dotInfoToDb->writeToDb($result);

		// STEP 4: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_TREE);
		$root = LayerElement::model()->findByPk($rootId);
		$root->accept($layout);
		
		// STEP 5: calculate absolute translations
		Yii::app()->layerX3dCalculator->calculate($root);
		$layers = LayerElement::model()->findAll();
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$this->render('index', array(root => $root, layers=>$layers));
	}
	
}
