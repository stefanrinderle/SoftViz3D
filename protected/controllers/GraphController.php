<?php

class GraphController extends BaseX3dController
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex()
	{
		$startTime = $this->getTime();
		
		// STEP 1: Load input dot file
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
 		try {
 			/* reset database */
 			TreeElement::model()->deleteAll();
 			EdgeElement::model()->deleteAll();
			
 			// STEP 2: Load input dot file into db
 			$result = Yii::app()->ownDotParser->parse($filename);
 			$edges = $result[edges];
 			$rootId = $result[rootId];
 		} catch (Exception $e) {
 			$exception = $e;
 			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
 			//TODO render another layout file and exit
 		}
		
 		echo "Calculation time write to db: " . $this->getTimeDifference($startTime) . "<br />";
		
 		$childLayer = LayerElement::model()->findAllByAttributes(
 				array('parent_id'=>$rootId));
 		
		while (count($childLayer) < 2) {
			$rootId = $childLayer[0]->id;
			$childLayer = LayerElement::model()->findAllByAttributes(
					array('parent_id'=>$rootId));
		}
		
		// STEP 3: Normalize edges
		Yii::app()->edgeExpander->execute($edges);
		
		echo "Calculation time edge expander: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 4: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_GRAPH);
		$root = LayerElement::model()->findByPk($rootId);
		$root->accept($layout);
		
		echo "Calculation time Layoutvisitor: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 5: calculate absolute translations
		Yii::app()->layerX3dCalculator->calculate($root);
		$layers = LayerElement::model()->findAll();
		
		echo "Calculation time absolute translations: " . $this->getTimeDifference($startTime) . "<br />";
		
// 		echo "RENDERING!";
		// STEP 5: show the calculated layout
		$this->render('index', array(root => $root, layers=>$layers));
	}
}
