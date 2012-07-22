<?php

class GraphController extends BaseX3dController
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		$startTime = $this->getTime();
		
		// STEP 1: Load input dot file
		$result = $this->loadFiletoDb();
		$edges = $result['edges'];
		$rootId = $result['rootId'];
		
		//echo "Calculation time write to db: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 2: Normalize edges
		Yii::app()->edgeExpander->execute($edges);
		
		//echo "Calculation time edge expander: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 3: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_GRAPH);
		$root = LayerElement::model()->findByPk($rootId);
		$root->accept($layout);
		
		
		//echo "Calculation time Layoutvisitor: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 4: calculate absolute translations
		Yii::app()->layerX3dCalculator->calculate($root);
		$layers = LayerElement::model()->findAll();
		
		//echo "Calculation time absolute translations: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 5: show the calculated layout
		$this->render('index', array('root' => $root, 'layers' => $layers));
	}
}
